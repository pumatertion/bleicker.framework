<?php
namespace Bleicker\Framework\Http;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Request
 *
 * @package Bleicker\Framework\Http
 */
interface RequestInterface {

	/**
	 * Returns the host name.
	 * This method can read the client port from the "X-Forwarded-Host" header
	 * when trusted proxies were set via "setTrustedProxies()".
	 * The "X-Forwarded-Host" header must contain the client host name.
	 * If your reverse proxy uses a different header name than "X-Forwarded-Host",
	 * configure it via "setTrustedHeaderName()" with the "client-host" key.
	 *
	 * @return string
	 * @throws \UnexpectedValueException when the host name is invalid
	 * @api
	 */
	public function getHost();

	/**
	 * Returns the client IP address.
	 * This method can read the client IP address from the "X-Forwarded-For" header
	 * when trusted proxies were set via "setTrustedProxies()". The "X-Forwarded-For"
	 * header value is a comma+space separated list of IP addresses, the left-most
	 * being the original client, and each successive proxy that passed the request
	 * adding the IP address where it received the request from.
	 * If your reverse proxy uses a different header name than "X-Forwarded-For",
	 * ("Client-Ip" for instance), configure it via "setTrustedHeaderName()" with
	 * the "client-ip" key.
	 *
	 * @return string The client IP address
	 * @see getClientIps()
	 * @see http://en.wikipedia.org/wiki/X-Forwarded-For
	 * @api
	 */
	public function getClientIp();

	/**
	 * Sets a list of trusted host patterns.
	 * You should only list the hosts you manage using regexs.
	 *
	 * @param array $hostPatterns A list of trusted host patterns
	 */
	public static function setTrustedHosts(array $hostPatterns);

	/**
	 * Returns the request body content.
	 *
	 * @param bool $asResource If true, a resource will be returned
	 * @return string|resource The request body content or a resource to read the body stream.
	 * @throws \LogicException
	 */
	public function getContent($asResource = FALSE);

	/**
	 * Gets a list of charsets acceptable by the client browser.
	 *
	 * @return array List of charsets in preferable order
	 * @api
	 */
	public function getCharsets();

	/**
	 * Gets a list of content types acceptable by the client browser.
	 *
	 * @return array List of content types in preferable order
	 * @api
	 */
	public function getAcceptableContentTypes();

	/**
	 * Returns the path being requested relative to the executed script.
	 * The path info always starts with a /.
	 * Suppose this request is instantiated from /mysite on localhost:
	 *  * http://localhost/mysite              returns an empty string
	 *  * http://localhost/mysite/about        returns '/about'
	 *  * http://localhost/mysite/enco%20ded   returns '/enco%20ded'
	 *  * http://localhost/mysite/about?var=1  returns '/about'
	 *
	 * @return string The raw path (i.e. not urldecoded)
	 * @api
	 */
	public function getPathInfo();

	/**
	 * Returns the root URL from which this request is executed.
	 * The base URL never ends with a /.
	 * This is similar to getBasePath(), except that it also includes the
	 * script filename (e.g. index.php) if one exists.
	 *
	 * @return string The raw URL (i.e. not urldecoded)
	 * @api
	 */
	public function getBaseUrl();

	/**
	 * Generates the normalized query string for the Request.
	 * It builds a normalized query string, where keys/value pairs are alphabetized
	 * and have consistent escaping.
	 *
	 * @return string|null A normalized query string for the Request
	 * @api
	 */
	public function getQueryString();

	/**
	 * Returns the HTTP host being requested.
	 * The port name will be appended to the host if it's non-standard.
	 *
	 * @return string
	 * @api
	 */
	public function getHttpHost();

	/**
	 * Gets the Session.
	 *
	 * @return SessionInterface|null The session
	 * @api
	 */
	public function getSession();

	/**
	 * Gets the Etags.
	 *
	 * @return array The entity tags
	 */
	public function getETags();

	/**
	 * Whether the request contains a Session object.
	 * This method does not give any information about the state of the session object,
	 * like whether the session is started or not. It is just a way to check if this Request
	 * is associated with a Session instance.
	 *
	 * @return bool true when the Request contains a Session object, false otherwise
	 * @api
	 */
	public function hasSession();

	/**
	 * Sets a list of trusted proxies.
	 * You should only list the reverse proxies that you manage directly.
	 *
	 * @param array $proxies A list of trusted proxies
	 * @api
	 */
	public static function setTrustedProxies(array $proxies);

	/**
	 * Gets the mime type associated with the format.
	 *
	 * @param string $format The format
	 * @return string The associated mime type (null if not found)
	 * @api
	 */
	public function getMimeType($format);

	/**
	 * Sets the locale.
	 *
	 * @param string $locale
	 * @api
	 */
	public function setLocale($locale);

	/**
	 * Generates a normalized URI for the given path.
	 *
	 * @param string $path A path to use instead of the current one
	 * @return string The normalized URI for the path
	 * @api
	 */
	public function getUriForPath($path);

	/**
	 * Returns the root path from which this request is executed.
	 * Suppose that an index.php file instantiates this request object:
	 *  * http://localhost/index.php         returns an empty string
	 *  * http://localhost/index.php/page    returns an empty string
	 *  * http://localhost/web/index.php     returns '/web'
	 *  * http://localhost/we%20b/index.php  returns '/we%20b'
	 *
	 * @return string The raw path (i.e. not urldecoded)
	 * @api
	 */
	public function getBasePath();

	/**
	 * Gets the list of trusted host patterns.
	 *
	 * @return array An array of trusted host patterns.
	 */
	public static function getTrustedHosts();

	/**
	 * Gets the request's scheme.
	 *
	 * @return string
	 * @api
	 */
	public function getScheme();

	/**
	 * Normalizes a query string.
	 * It builds a normalized query string, where keys/value pairs are alphabetized,
	 * have consistent escaping and unneeded delimiters are removed.
	 *
	 * @param string $qs Query string
	 * @return string A normalized query string for the Request
	 */
	public static function normalizeQueryString($qs);

	/**
	 * Checks whether the request is secure or not.
	 * This method can read the client port from the "X-Forwarded-Proto" header
	 * when trusted proxies were set via "setTrustedProxies()".
	 * The "X-Forwarded-Proto" header must contain the protocol: "https" or "http".
	 * If your reverse proxy uses a different header name than "X-Forwarded-Proto"
	 * ("SSL_HTTPS" for instance), configure it via "setTrustedHeaderName()" with
	 * the "client-proto" key.
	 *
	 * @return bool
	 * @api
	 */
	public function isSecure();

	/**
	 * Returns true if the request is a XMLHttpRequest.
	 * It works if your JavaScript library sets an X-Requested-With HTTP header.
	 * It is known to work with common JavaScript frameworks:
	 *
	 * @link http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
	 * @return bool true if the request is an XMLHttpRequest, false otherwise
	 * @api
	 */
	public function isXmlHttpRequest();

	/**
	 * Gets the list of trusted proxies.
	 *
	 * @return array An array of trusted proxies.
	 */
	public static function getTrustedProxies();

	/**
	 * Gets a list of encodings acceptable by the client browser.
	 *
	 * @return array List of encodings in preferable order
	 */
	public function getEncodings();

	/**
	 * Get the default locale.
	 *
	 * @return string
	 */
	public function getDefaultLocale();

	/**
	 * Checks if the request method is of specified type.
	 *
	 * @param string $method Uppercase request method (GET, POST etc).
	 * @return bool
	 */
	public function isMethod($method);

	/**
	 * Returns the password.
	 *
	 * @return string|null
	 */
	public function getPassword();

	/**
	 * Sets the default locale.
	 *
	 * @param string $locale
	 * @api
	 */
	public function setDefaultLocale($locale);

	/**
	 * Sets the name for trusted headers.
	 * The following header keys are supported:
	 *  * Request::HEADER_CLIENT_IP:    defaults to X-Forwarded-For   (see getClientIp())
	 *  * Request::HEADER_CLIENT_HOST:  defaults to X-Forwarded-Host  (see getHost())
	 *  * Request::HEADER_CLIENT_PORT:  defaults to X-Forwarded-Port  (see getPort())
	 *  * Request::HEADER_CLIENT_PROTO: defaults to X-Forwarded-Proto (see getScheme() and isSecure())
	 * Setting an empty value allows to disable the trusted header for the given key.
	 *
	 * @param string $key The header key
	 * @param string $value The header name
	 * @throws \InvalidArgumentException
	 */
	public static function setTrustedHeaderName($key, $value);

	/**
	 * Sets a callable able to create a Request instance.
	 * This is mainly useful when you need to override the Request class
	 * to keep BC with an existing system. It should not be used for any
	 * other purpose.
	 *
	 * @param callable|null $callable A PHP callable
	 */
	public static function setFactory($callable);

	/**
	 * @return bool
	 */
	public function isNoCache();

	/**
	 * Gets the request "intended" method.
	 * If the X-HTTP-Method-Override header is set, and if the method is a POST,
	 * then it is used to determine the "real" intended HTTP method.
	 * The _method request parameter can also be used to determine the HTTP method,
	 * but only if enableHttpMethodParameterOverride() has been called.
	 * The method is always an uppercased string.
	 *
	 * @return string The request method
	 * @api
	 * @see getRealMethod()
	 */
	public function getMethod();

	/**
	 * Sets the request format.
	 *
	 * @param string $format The request format.
	 * @api
	 */
	public function setRequestFormat($format);

	/**
	 * Gets the scheme and HTTP host.
	 * If the URL was called with basic authentication, the user
	 * and the password are not added to the generated string.
	 *
	 * @return string The scheme and HTTP host
	 */
	public function getSchemeAndHttpHost();

	/**
	 * Creates a Request based on a given URI and configuration.
	 * The information contained in the URI always take precedence
	 * over the other information (server and parameters).
	 *
	 * @param string $uri The URI
	 * @param string $method The HTTP method
	 * @param array $parameters The query (GET) or request (POST) parameters
	 * @param array $cookies The request cookies ($_COOKIE)
	 * @param array $files The request files ($_FILES)
	 * @param array $server The server parameters ($_SERVER)
	 * @param string $content The raw body data
	 * @return Request A Request instance
	 * @api
	 */
	public static function create($uri, $method = 'GET', $parameters = array(), $cookies = array(), $files = array(), $server = array(), $content = NULL);

	/**
	 * Clones a request and overrides some of its parameters.
	 *
	 * @param array $query The GET parameters
	 * @param array $request The POST parameters
	 * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
	 * @param array $cookies The COOKIE parameters
	 * @param array $files The FILES parameters
	 * @param array $server The SERVER parameters
	 * @return Request The duplicated request
	 * @api
	 */
	public function duplicate(array $query = NULL, array $request = NULL, array $attributes = NULL, array $cookies = NULL, array $files = NULL, array $server = NULL);

	/**
	 * Gets a "parameter" value.
	 * This method is mainly useful for libraries that want to provide some flexibility.
	 * Order of precedence: GET, PATH, POST
	 * Avoid using this method in controllers:
	 *  * slow
	 *  * prefer to get from a "named" source
	 * It is better to explicitly get request parameters from the appropriate
	 * public property instead (query, attributes, request).
	 *
	 * @param string $key the key
	 * @param mixed $default the default value
	 * @param bool $deep is parameter deep in multidimensional array
	 * @return mixed
	 */
	public function get($key, $default = NULL, $deep = FALSE);

	/**
	 * Generates a normalized URI (URL) for the Request.
	 *
	 * @return string A normalized URI (URL) for the Request
	 * @see getQueryString()
	 * @api
	 */
	public function getUri();

	/**
	 * Gets the user info.
	 *
	 * @return string A user name and, optionally, scheme-specific information about how to gain authorization to access the server
	 */
	public function getUserInfo();

	/**
	 * Gets the request format.
	 * Here is the process to determine the format:
	 *  * format defined by the user (with setRequestFormat())
	 *  * _format request parameter
	 *  * $default
	 *
	 * @param string $default The default format
	 * @return string The request format
	 * @api
	 */
	public function getRequestFormat($default = 'html');

	/**
	 * Creates a new request with values from PHP's super globals.
	 *
	 * @return Request A new request
	 * @api
	 */
	public static function createFromGlobals();

	/**
	 * Sets the parameters for this request.
	 * This method also re-initializes all properties.
	 *
	 * @param array $query The GET parameters
	 * @param array $request The POST parameters
	 * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
	 * @param array $cookies The COOKIE parameters
	 * @param array $files The FILES parameters
	 * @param array $server The SERVER parameters
	 * @param string $content The raw body data
	 * @api
	 */
	public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = NULL);

	/**
	 * Gets the trusted proxy header name.
	 *
	 * @param string $key The header key
	 * @return string The header name
	 * @throws \InvalidArgumentException
	 */
	public static function getTrustedHeaderName($key);

	/**
	 * Returns the preferred language.
	 *
	 * @param array $locales An array of ordered available locales
	 * @return string|null The preferred locale
	 * @api
	 */
	public function getPreferredLanguage(array $locales = NULL);

	/**
	 * Returns the requested URI (path and query string).
	 *
	 * @return string The raw URI (i.e. not URI decoded)
	 * @api
	 */
	public function getRequestUri();

	/**
	 * Returns the client IP addresses.
	 * In the returned array the most trusted IP address is first, and the
	 * least trusted one last. The "real" client IP address is the last one,
	 * but this is also the least trusted one. Trusted proxies are stripped.
	 * Use this method carefully; you should use getClientIp() instead.
	 *
	 * @return array The client IP addresses
	 * @see getClientIp()
	 */
	public function getClientIps();

	/**
	 * Get the locale.
	 *
	 * @return string
	 */
	public function getLocale();

	/**
	 * Associates a format with mime types.
	 *
	 * @param string $format The format
	 * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used as the content type)
	 * @api
	 */
	public function setFormat($format, $mimeTypes);

	/**
	 * Gets the format associated with the request.
	 *
	 * @return string|null The format (null if no content type is present)
	 * @api
	 */
	public function getContentType();

	/**
	 * Checks whether the method is safe or not.
	 *
	 * @return bool
	 * @api
	 */
	public function isMethodSafe();

	/**
	 * Returns the user.
	 *
	 * @return string|null
	 */
	public function getUser();

	/**
	 * Returns the port on which the request is made.
	 * This method can read the client port from the "X-Forwarded-Port" header
	 * when trusted proxies were set via "setTrustedProxies()".
	 * The "X-Forwarded-Port" header must contain the client port.
	 * If your reverse proxy uses a different header name than "X-Forwarded-Port",
	 * configure it via "setTrustedHeaderName()" with the "client-port" key.
	 *
	 * @return string
	 * @api
	 */
	public function getPort();

	/**
	 * Gets the format associated with the mime type.
	 *
	 * @param string $mimeType The associated mime type
	 * @return string|null The format (null if not found)
	 * @api
	 */
	public function getFormat($mimeType);

	/**
	 * Returns current script name.
	 *
	 * @return string
	 * @api
	 */
	public function getScriptName();

	/**
	 * Whether the request contains a Session which was started in one of the
	 * previous requests.
	 *
	 * @return bool
	 * @api
	 */
	public function hasPreviousSession();

	/**
	 * Gets a list of languages acceptable by the client browser.
	 *
	 * @return array Languages ordered in the user browser preferences
	 * @api
	 */
	public function getLanguages();

	/**
	 * Overrides the PHP global variables according to this request instance.
	 * It overrides $_GET, $_POST, $_REQUEST, $_SERVER, $_COOKIE.
	 * $_FILES is never overridden, see rfc1867
	 *
	 * @api
	 */
	public function overrideGlobals();

	/**
	 * Sets the request method.
	 *
	 * @param string $method
	 * @api
	 */
	public function setMethod($method);

	/**
	 * Sets the Session.
	 *
	 * @param SessionInterface $session The Session
	 * @api
	 */
	public function setSession(SessionInterface $session);

	/**
	 * Enables support for the _method request parameter to determine the intended HTTP method.
	 * Be warned that enabling this feature might lead to CSRF issues in your code.
	 * Check that you are using CSRF tokens when required.
	 * If the HTTP method parameter override is enabled, an html-form with method "POST" can be altered
	 * and used to send a "PUT" or "DELETE" request via the _method request parameter.
	 * If these methods are not protected against CSRF, this presents a possible vulnerability.
	 * The HTTP method can only be overridden when the real HTTP method is POST.
	 */
	public static function enableHttpMethodParameterOverride();

	/**
	 * Gets the "real" request method.
	 *
	 * @return string The request method
	 * @see getMethod()
	 */
	public function getRealMethod();

	/**
	 * Checks whether support for the _method request parameter is enabled.
	 *
	 * @return bool True when the _method request parameter is enabled, false otherwise
	 */
	public static function getHttpMethodParameterOverride();

	/**
	 * @return HeaderBag
	 */
	public function getHeaders();

	/**
	 * @return ParameterBag
	 */
	public function getParameter();
}
