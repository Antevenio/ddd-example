<?php


namespace Antevenio\DddExample\Domain\ValueObject;

class EmailAddress
{
    const EMPTY_NAME = '';

    /**
     * @var Email
     */
    private $email;

    private $name;

    /**
     * Address constructor.
     * @param string $email
     * @param string $name
     */
    private function __construct(string $email, $name = '')
    {
        $this->email = Email::create($email);
        $this->name = $name;
    }

    public static function create($address)
    {
        $email = $address;
        $name = self::EMPTY_NAME;
        if (is_array($address)) {
            reset($address);
            $email = key($address);
            $name = isset($address[$email]) ? $address[$email] : self::EMPTY_NAME;
            return new self($email, $name);
        }
        return self::createFromString($address);
    }

    private static function createFromString($address)
    {
        $email = null;
        $name = null;
        preg_match('/^((?P<name>.*)<(?P<namedEmail>[^>]+)>|(?P<email>.+))$/', $address, $matches);
        if (isset($matches['name'])) {
            $name = trim($matches['name']);
        }
        if (empty($name)) {
            $name = null;
        }
        if (isset($matches['namedEmail'])) {
            $email = $matches['namedEmail'];
        }
        if (isset($matches['email'])) {
            $email = $matches['email'];
        }
        $email = trim($email);

        return new self($email, $name);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
