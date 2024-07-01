<?php

namespace Core\Http;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Request
{
  private string $method;
  private string $uri;

  /** @var mixed[] */
  private array $params;

  /** @var array<string, string> */
  private array $headers;

  private string $body;

  public function __construct()
  {
    $this->method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    $this->uri = $_SERVER['REQUEST_URI'];
    $this->params = $_REQUEST;
    $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    $this->body = file_get_contents('php://input');
  }

  public function getMethod(): string
  {
    return $this->method;
  }

  public function getUri(): string
  {
    return $this->uri;
  }

  /** @return mixed[] */
  public function getParams(): array
  {
    return $this->params;
  }

  /** @return array<string, string> */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /** @param mixed[] $params */
  public function addParams(array $params): void
  {
    $this->params = array_merge($this->params, $params);
  }

  public function acceptJson(): bool
  {
    return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/json');
  }

  public function getParam(string $key, mixed $default = null): mixed
  {
    return $this->params[$key] ?? $default;
  }

  public function getBody(): string
  {
    return $this->body;
  }

  public function setBody(string $body): void
  {
    $this->body = $body;
  }

  public function getHeader(string $name): ?string
  {
    return $this->headers[$name] ?? null;
  }
}
