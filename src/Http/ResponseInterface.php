<?php
namespace Bleicker\Framework\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Response
 *
 * @package Bleicker\Framework\Http
 */
interface ResponseInterface {

	/**
	 * Sets the Expires HTTP header with a DateTime instance.
	 * Passing null as value will remove the header.
	 *
	 * @param \DateTime|null $date A \DateTime instance or null to remove the header
	 * @return Response
	 * @api
	 */
	public function setExpires(\DateTime $date = NULL);

	/**
	 * Returns the literal value of the ETag HTTP header.
	 *
	 * @return string|null The ETag HTTP header or null if it does not exist
	 * @api
	 */
	public function getEtag();

	/**
	 * Returns the age of the response.
	 *
	 * @return int The age of the response in seconds
	 */
	public function getAge();

	/**
	 * Sets the Last-Modified HTTP header with a DateTime instance.
	 * Passing null as value will remove the header.
	 *
	 * @param \DateTime|null $date A \DateTime instance or null to remove the header
	 * @return Response
	 * @api
	 */
	public function setLastModified(\DateTime $date = NULL);

	/**
	 * Returns true if the response must be revalidated by caches.
	 * This method indicates that the response must not be served stale by a
	 * cache in any circumstance without first revalidating with the origin.
	 * When present, the TTL of the response should not be overridden to be
	 * greater than the value provided by the origin.
	 *
	 * @return bool true if the response must be revalidated by a cache, false otherwise
	 * @api
	 */
	public function mustRevalidate();

	/**
	 * Marks the response as "private".
	 * It makes the response ineligible for serving other clients.
	 *
	 * @return Response
	 * @api
	 */
	public function setPrivate();

	/**
	 * Retrieves the status code for the current web response.
	 *
	 * @return int Status code
	 * @api
	 */
	public function getStatusCode();

	/**
	 * Was there a server side error?
	 *
	 * @return bool
	 * @api
	 */
	public function isServerError();

	/**
	 * Sets the ETag value.
	 *
	 * @param string|null $etag The ETag unique identifier or null to remove the header
	 * @param bool $weak Whether you want a weak ETag or not
	 * @return Response
	 * @api
	 */
	public function setEtag($etag = NULL, $weak = FALSE);

	/**
	 * Gets the current response content.
	 *
	 * @return string Content
	 * @api
	 */
	public function getContent();

	/**
	 * Returns an array of header names given in the Vary header.
	 *
	 * @return array An array of Vary names
	 * @api
	 */
	public function getVary();

	/**
	 * Is response informative?
	 *
	 * @return bool
	 * @api
	 */
	public function isInformational();

	/**
	 * Returns the number of seconds after the time specified in the response's Date
	 * header when the response should no longer be considered fresh.
	 * First, it checks for a s-maxage directive, then a max-age directive, and then it falls
	 * back on an expires header. It returns null when no maximum age can be established.
	 *
	 * @return int|null Number of seconds
	 * @api
	 */
	public function getMaxAge();

	/**
	 * Is the response a not found error?
	 *
	 * @return bool
	 * @api
	 */
	public function isNotFound();

	/**
	 * Returns the Date header as a DateTime instance.
	 *
	 * @return \DateTime A \DateTime instance
	 * @throws \RuntimeException When the header is not parseable
	 * @api
	 */
	public function getDate();

	/**
	 * Returns true if the response includes headers that can be used to validate
	 * the response with the origin server using a conditional GET request.
	 *
	 * @return bool true if the response is validateable, false otherwise
	 * @api
	 */
	public function isValidateable();

	/**
	 * Returns the response's time-to-live in seconds.
	 * It returns null when no freshness information is present in the response.
	 * When the responses TTL is <= 0, the response may not be served from cache without first
	 * revalidating with the origin.
	 *
	 * @return int|null The TTL in seconds
	 * @api
	 */
	public function getTtl();

	/**
	 * Marks the response stale by setting the Age header to be equal to the maximum age of the response.
	 *
	 * @return Response
	 * @api
	 */
	public function expire();

	/**
	 * Is the response OK?
	 *
	 * @return bool
	 * @api
	 */
	public function isOk();

	/**
	 * Sets the response's cache headers (validation and/or expiration).
	 * Available options are: etag, last_modified, max_age, s_maxage, private, and public.
	 *
	 * @param array $options An array of cache options
	 * @return Response
	 * @throws \InvalidArgumentException
	 * @api
	 */
	public function setCache(array $options);

	/**
	 * Returns true if the response includes a Vary header.
	 *
	 * @return bool true if the response includes a Vary header, false otherwise
	 * @api
	 */
	public function hasVary();

	/**
	 * Retrieves the response charset.
	 *
	 * @return string Character set
	 * @api
	 */
	public function getCharset();

	/**
	 * Marks the response as "public".
	 * It makes the response eligible for serving other clients.
	 *
	 * @return Response
	 * @api
	 */
	public function setPublic();

	/**
	 * Is the response a redirect of some form?
	 *
	 * @param string $location
	 * @return bool
	 * @api
	 */
	public function isRedirect($location = NULL);

	/**
	 * Returns the Response as an HTTP string.
	 * The string representation of the Response is the same as the
	 * one that will be sent to the client only if the prepare() method
	 * has been called before.
	 *
	 * @return string The Response as an HTTP string
	 * @see prepare()
	 */
	public function __toString();

	/**
	 * Sets the response's time-to-live for shared caches.
	 * This method adjusts the Cache-Control/s-maxage directive.
	 *
	 * @param int $seconds Number of seconds
	 * @return Response
	 * @api
	 */
	public function setTtl($seconds);

	/**
	 * Sets the Vary header.
	 *
	 * @param string|array $headers
	 * @param bool $replace Whether to replace the actual value of not (true by default)
	 * @return Response
	 * @api
	 */
	public function setVary($headers, $replace = TRUE);

	/**
	 * Is the response forbidden?
	 *
	 * @return bool
	 * @api
	 */
	public function isForbidden();

	/**
	 * Returns the value of the Expires header as a DateTime instance.
	 *
	 * @return \DateTime|null A DateTime instance or null if the header does not exist
	 * @api
	 */
	public function getExpires();

	/**
	 * Sets the response's time-to-live for private/client caches.
	 * This method adjusts the Cache-Control/max-age directive.
	 *
	 * @param int $seconds Number of seconds
	 * @return Response
	 * @api
	 */
	public function setClientTtl($seconds);

	/**
	 * Sets the number of seconds after which the response should no longer be considered fresh by shared caches.
	 * This methods sets the Cache-Control s-maxage directive.
	 *
	 * @param int $value Number of seconds
	 * @return Response
	 * @api
	 */
	public function setSharedMaxAge($value);

	/**
	 * Sets the response status code.
	 *
	 * @param int $code HTTP status code
	 * @param mixed $text HTTP status text
	 * If the status text is null it will be automatically populated for the known
	 * status codes and left empty otherwise.
	 * @return Response
	 * @throws \InvalidArgumentException When the HTTP status code is not valid
	 * @api
	 */
	public function setStatusCode($code, $text = NULL);

	/**
	 * Sets the HTTP protocol version (1.0 or 1.1).
	 *
	 * @param string $version The HTTP protocol version
	 * @return Response
	 * @api
	 */
	public function setProtocolVersion($version);

	/**
	 * Cleans or flushes output buffers up to target level.
	 * Resulting level can be greater than target level if a non-removable buffer has been encountered.
	 *
	 * @param int $targetLevel The target output buffering level
	 * @param bool $flush Whether to flush or clean the buffers
	 */
	public static function closeOutputBuffers($targetLevel, $flush);

	/**
	 * Is response invalid?
	 *
	 * @return bool
	 * @api
	 */
	public function isInvalid();

	/**
	 * Is the response empty?
	 *
	 * @return bool
	 * @api
	 */
	public function isEmpty();

	/**
	 * Sends content for the current web response.
	 *
	 * @return Response
	 */
	public function sendContent();

	/**
	 * Factory method for chainability.
	 * Example:
	 *     return Response::create($body, 200)
	 *         ->setSharedMaxAge(300);
	 *
	 * @param mixed $content The response content, see setContent()
	 * @param int $status The response status code
	 * @param array $headers An array of response headers
	 * @return Response
	 */
	public static function create($content = '', $status = 200, $headers = array());

	/**
	 * Is the response a redirect?
	 *
	 * @return bool
	 * @api
	 */
	public function isRedirection();

	/**
	 * Returns true if the response is "fresh".
	 * Fresh responses may be served from cache without any interaction with the
	 * origin. A response is considered fresh when it includes a Cache-Control/max-age
	 * indicator or Expires header and the calculated age is less than the freshness lifetime.
	 *
	 * @return bool true if the response is fresh, false otherwise
	 * @api
	 */
	public function isFresh();

	/**
	 * Prepares the Response before it is sent to the client.
	 * This method tweaks the Response to ensure that it is
	 * compliant with RFC 2616. Most of the changes are based on
	 * the Request that is "associated" with this Response.
	 *
	 * @param Request $request A Request instance
	 * @return Response The current response.
	 */
	public function prepare(Request $request);

	/**
	 * Gets the HTTP protocol version.
	 *
	 * @return string The HTTP protocol version
	 * @api
	 */
	public function getProtocolVersion();

	/**
	 * Is response successful?
	 *
	 * @return bool
	 * @api
	 */
	public function isSuccessful();

	/**
	 * Returns the Last-Modified HTTP header as a DateTime instance.
	 *
	 * @return \DateTime|null A DateTime instance or null if the header does not exist
	 * @throws \RuntimeException When the HTTP header is not parseable
	 * @api
	 */
	public function getLastModified();

	/**
	 * Is there a client error?
	 *
	 * @return bool
	 * @api
	 */
	public function isClientError();

	/**
	 * Sets the response charset.
	 *
	 * @param string $charset Character set
	 * @return Response
	 * @api
	 */
	public function setCharset($charset);

	/**
	 * Returns true if the response is worth caching under any circumstance.
	 * Responses marked "private" with an explicit Cache-Control directive are
	 * considered uncacheable.
	 * Responses with neither a freshness lifetime (Expires, max-age) nor cache
	 * validator (Last-Modified, ETag) are considered uncacheable.
	 *
	 * @return bool true if the response is worth caching, false otherwise
	 * @api
	 */
	public function isCacheable();

	/**
	 * Sets the number of seconds after which the response should no longer be considered fresh.
	 * This methods sets the Cache-Control max-age directive.
	 *
	 * @param int $value Number of seconds
	 * @return Response
	 * @api
	 */
	public function setMaxAge($value);

	/**
	 * Sets the Date header.
	 *
	 * @param \DateTime $date A \DateTime instance
	 * @return Response
	 * @api
	 */
	public function setDate(\DateTime $date);

	/**
	 * Sends HTTP headers.
	 *
	 * @return Response
	 */
	public function sendHeaders();

	/**
	 * Determines if the Response validators (ETag, Last-Modified) match
	 * a conditional value specified in the Request.
	 * If the Response is not modified, it sets the status code to 304 and
	 * removes the actual content by calling the setNotModified() method.
	 *
	 * @param Request $request A Request instance
	 * @return bool true if the Response validators match the Request, false otherwise
	 * @api
	 */
	public function isNotModified(Request $request);

	/**
	 * Sets the response content.
	 * Valid types are strings, numbers, null, and objects that implement a __toString() method.
	 *
	 * @param mixed $content Content that can be cast to string
	 * @return Response
	 * @throws \UnexpectedValueException
	 * @api
	 */
	public function setContent($content);

	/**
	 * Sends HTTP headers and content.
	 *
	 * @return Response
	 * @api
	 */
	public function send();

	/**
	 * Modifies the response so that it conforms to the rules defined for a 304 status code.
	 * This sets the status, removes the body, and discards any headers
	 * that MUST NOT be included in 304 responses.
	 *
	 * @return Response
	 * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
	 * @api
	 */
	public function setNotModified();
}