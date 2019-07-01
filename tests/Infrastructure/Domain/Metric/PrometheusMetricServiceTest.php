<?php


namespace Antevenio\DddExample\Infrastructure\Domain\Metric;

use Antevenio\DddExample\Domain\Metric\Counter;
use Antevenio\DddExample\Domain\Metric\Gauge;
use Antevenio\DddExample\Domain\Metric\Histogram;
use Antevenio\DddExample\Domain\Metric\MetricService;
use Antevenio\DddExample\Infrastructure\Domain\Metric\Prometheus\PrometheusMetricService;
use PHPUnit\Framework\TestCase;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

class PrometheusMetricServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $namespace = 'myNamespace';
    /**
     * @var array
     */
    private $config;
    /**
     * @var MetricService
     */
    private $metricService;

    protected function setUp(): void
    {
        parent::setUp();
        $collectorRegistry = new CollectorRegistry(new InMemory());
        $this->config = [
            'namespace' => 'myNamespace',
            'myDefinedCounterWithHelp' => [
                'help' => 'myCounter help'
            ],
            'myDefinedCounterWithLabels' => [
                'labels' => ['label1', 'label2']
            ],
            'myDefinedGaugeWithHelp' => [
                'help' => 'myGauge help'
            ],
            'myDefinedGaugeWithLabels' => [
                'labels' => ['label1', 'label2']
            ],
            'myDefinedHistogramWithHelp' => [
                'help' => 'myGauge help'
            ],
            'myDefinedHistogramWithLabels' => [
                'labels' => ['label1', 'label2']
            ],
            'myDefinedHistogramWithBuckets' => [
                'buckets' => [0.1, 0.5, 1]
            ],
        ];
        $this->metricService = new PrometheusMetricService(
            $collectorRegistry,
            $this->config
        );
    }

    public function testShouldBeCreated()
    {
        $this->assertNotNull($this->metricService);
    }

    public function testShouldBeAInstanceOfMetricService()
    {
        $this->assertInstanceOf(MetricService::class, $this->metricService);
    }

    public function testShouldGetACounter()
    {
        $name = 'myCounter';
        $counter = $this->metricService->getCounter($name);
        $this->assertInstanceOf(Counter::class, $counter);
    }

    public function testShouldIncrementACounter()
    {
        $name = 'myCounter';

        $counter = $this->metricService->getCounter($name);
        $counter->increment();

        $metrics = $this->metricService->getMetrics();
        $this->assertStringContainsString("{$this->namespace}_{$name} 1", $metrics);
    }

    public function testShouldGetMetricsCounterHelpFromConfig()
    {
        $name = 'myDefinedCounterWithHelp';
        $counter = $this->metricService->getCounter($name);

        $counter->increment();
        $metrics = $this->metricService->getMetrics();

        $expectedHelp = $this->config[$name]['help'];
        $this->assertStringContainsString(
            "# HELP {$this->namespace}_{$name} $expectedHelp",
            $metrics
        );
    }

    public function testShouldGetMetricsCounterLabelsFromConfig()
    {
        $name = 'myDefinedCounterWithLabels';
        $counter = $this->metricService->getCounter($name);

        $counter->increment(['foo', 'bar']);
        $metrics = $this->metricService->getMetrics();

        $expectedLabels = 'label1="foo",label2="bar"';
        $this->assertStringContainsString(
            "{$this->namespace}_{$name}{{$expectedLabels}} 1",
            $metrics
        );
    }

    public function testShouldGetAGauge()
    {
        $name = 'myGauge';
        $gauge = $this->metricService->getGauge($name);
        $this->assertInstanceOf(Gauge::class, $gauge);
    }

    public function testShouldSetAGaugeValue()
    {
        $name = 'myGauge';
        $gauge = $this->metricService->getGauge($name);

        $value = 2;
        $gauge->set($value);

        $metrics = $this->metricService->getMetrics();
        $this->assertStringContainsString("{$this->namespace}_{$name} $value", $metrics);
    }

    public function testShouldGetMetricsGaugeHelpFromConfig()
    {
        $name = 'myDefinedGaugeWithHelp';

        $gauge = $this->metricService->getGauge($name);
        $value = 2;
        $gauge->set($value);

        $metrics = $this->metricService->getMetrics();

        $expectedHelp = $this->config[$name]['help'];
        $this->assertStringContainsString(
            "# HELP {$this->namespace}_{$name} $expectedHelp",
            $metrics
        );
    }

    public function testShouldGetMetricsGaugeLabelsFromConfig()
    {
        $name = 'myDefinedGaugeWithLabels';

        $gauge = $this->metricService->getGauge($name);
        $value = 2;
        $gauge->set($value, ['foo', 'bar']);

        $metrics = $this->metricService->getMetrics();
        $expectedLabels = 'label1="foo",label2="bar"';
        $this->assertStringContainsString(
            "{$this->namespace}_{$name}{{$expectedLabels}} 2",
            $metrics
        );
    }

    public function testShouldGetAnHistogram()
    {
        $name = 'myHistogram';
        $histogram = $this->metricService->getHistogram($name);
        $this->assertInstanceOf(Histogram::class, $histogram);
    }

    public function testShouldObserveAHistogramValue()
    {
        $name = 'myHistogram';

        $histogram = $this->metricService->getHistogram($name);
        $value = 0.5;
        $histogram->observe($value);

        $metrics = $this->metricService->getMetrics();

        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_count 1",
            $metrics
        );
        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_sum $value",
            $metrics
        );
        $defaultBuckets = \Prometheus\Histogram::getDefaultBuckets();
        foreach ($defaultBuckets as $bucket) {
            $bucketValue = $bucket >= $value ? 1 : 0;
            $this->assertStringContainsString(
                "{$this->namespace}_{$name}_bucket{le=\"{$bucket}\"} $bucketValue",
                $metrics
            );
        }
    }

    public function testShouldGetMetricsHistogramHelpFromConfig()
    {
        $name = 'myDefinedHistogramWithHelp';

        $histogram = $this->metricService->getHistogram($name);
        $value = 0.5;
        $histogram->observe($value);

        $metrics = $this->metricService->getMetrics();

        $expectedHelp = $this->config[$name]['help'];
        $this->assertStringContainsString(
            "# HELP {$this->namespace}_{$name} $expectedHelp",
            $metrics
        );
    }

    public function testShouldGetMetricsHistogramLabelsFromConfig()
    {
        $name = 'myDefinedHistogramWithLabels';

        $histogram = $this->metricService->getHistogram($name);
        $value = 0.5;
        $histogram->observe($value, ['foo', 'bar']);

        $metrics = $this->metricService->getMetrics();
        $expectedLabels = 'label1="foo",label2="bar"';

        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_count{{$expectedLabels}} 1",
            $metrics
        );
        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_sum{{$expectedLabels}} $value",
            $metrics
        );
        $defaultBuckets = \Prometheus\Histogram::getDefaultBuckets();
        foreach ($defaultBuckets as $bucket) {
            $bucketValue = $bucket >= $value ? 1 : 0;
            $this->assertStringContainsString(
                "{$this->namespace}_{$name}_bucket{{$expectedLabels},le=\"{$bucket}\"} $bucketValue",
                $metrics
            );
        }
    }


    public function testShouldGetMetricsHistogramBucketsFromConfig()
    {
        $name = 'myDefinedHistogramWithBuckets';

        $histogram = $this->metricService->getHistogram($name);
        $value = 0.5;
        $histogram->observe($value);

        $metrics = $this->metricService->getMetrics();

        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_count 1",
            $metrics
        );
        $this->assertStringContainsString(
            "{$this->namespace}_{$name}_sum $value",
            $metrics
        );
        $defaultBuckets = $this->config[$name]['buckets'];
        foreach ($defaultBuckets as $bucket) {
            $bucketValue = $bucket >= $value ? 1 : 0;
            $this->assertStringContainsString(
                "{$this->namespace}_{$name}_bucket{le=\"{$bucket}\"} $bucketValue",
                $metrics
            );
        }
    }
}
