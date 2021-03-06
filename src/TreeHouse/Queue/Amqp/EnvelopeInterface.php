<?php

namespace TreeHouse\Queue\Amqp;

interface EnvelopeInterface
{
    /**
     * Get the application id of the message.
     *
     * @return string The application id of the message.
     */
    public function getAppId();

    /**
     * Get the body of the message.
     *
     * @return string The contents of the message body.
     */
    public function getBody();

    /**
     * Get the content encoding of the message.
     *
     * @return string The content encoding of the message.
     */
    public function getContentEncoding();

    /**
     * Get the message content type.
     *
     * @return string The content type of the message.
     */
    public function getContentType();

    /**
     * Get the message correlation id.
     *
     * @return string The correlation id of the message.
     */
    public function getCorrelationId();

    /**
     * Get the delivery mode of the message.
     *
     * @return int The delivery mode of the message.
     */
    public function getDeliveryMode();

    /**
     * Get the delivery tag of the message.
     *
     * @return string The delivery tag of the message.
     */
    public function getDeliveryTag();

    /**
     * Get the exchange name on which the message was published.
     *
     * @return string The exchange name on which the message was published.
     */
    public function getExchangeName();

    /**
     * Get the expiration of the message.
     *
     * @return string The message expiration.
     */
    public function getExpiration();

    /**
     * Get a specific message header.
     *
     * @param string $headerKey Name of the header to get the value from.
     *
     * @return string|bool The contents of the specified header or false if not set.
     */
    public function getHeader($headerKey);

    /**
     * Get the headers of the message.
     *
     * @return array An array of key value pairs associated with the message.
     */
    public function getHeaders();

    /**
     * Get the message id of the message.
     *
     * @return string The message id
     */
    public function getMessageId();

    /**
     * Get the priority of the message.
     *
     * @return string The message priority.
     */
    public function getPriority();

    /**
     * Get the reply-to address of the message.
     *
     * @return string The contents of the reply to field.
     */
    public function getReplyTo();

    /**
     * Get the routing key of the message.
     *
     * @return string The message routing key.
     */
    public function getRoutingKey();

    /**
     * Get the timestamp of the message.
     *
     * @return string The message timestamp.
     */
    public function getTimestamp();

    /**
     * Get the message type.
     *
     * @return string The message type.
     */
    public function getType();

    /**
     * Get the message user id.
     *
     * @return string The message user id.
     */
    public function getUserId();

    /**
     * Whether this is a redelivery of the message.
     *
     * If this message has been delivered and `nack()` was called, the message will be put back on the queue to be
     * redelivered, at which point the message will always return true when this method is called.
     *
     * @return bool true if this is a redelivery, false otherwise.
     */
    public function isRedelivery();
}
