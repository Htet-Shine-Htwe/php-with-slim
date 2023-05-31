<?php

namespace App;
use App\Contracts\SessionInterface;
use App\DataObjects\SessionConfig;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;

class Session implements SessionInterface
{

    public function __construct(private readonly SessionConfig $options)
    {

    }

    public function start() :void
    {
        if($this->isActive())
        {
            throw new \RuntimeException('Session was already been started');
        }
        
        if(headers_sent($filename,$line))
        {
            throw new \RuntimeException('Header already sent');
        }

        session_set_cookie_params([
            'secure' => $this->options->secure,
            'httpOnly' =>$this->options->httpOnly ,
            'sameSite' => $this->options->sameSite->value ]);


        if(! empty($this->options->name))
        {
            session_name($this->options->name);
        }

        if(! session_start())
        {
            throw new SessionNotFoundException('Unable to start session');
        }

        // session_start();

    }

    public function close() :void
    {
        session_write_close();
    }

    public function isActive():bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }
}   
