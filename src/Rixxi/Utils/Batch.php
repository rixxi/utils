<?php

namespace Rixxi\Utils;


class Batch implements \ArrayAccess
{

	/** @var int */
	private $limit;

	/** @var int */
	private $counter = 0;

	/** @var callable */
	private $onFlush;

	/** @var array */
	private $values = array();


	/**
	 * Callback will receive all appended values as an array. When limit is reached flush is triggered.
	 *
	 * @param callable
	 * @param int
	 */
	public function __construct(callable $onFlush, $limit = 1000)
	{
		$this->onFlush = $onFlush;
		$this->limit = $limit;
	}


	public function __destruct()
	{
		$this->flush();
	}


	/**
	 * Adds value to batch. If limit is reached flush is triggered immediately.
	 *
	 * @param mixed
	 */
	public function append($value)
	{
		$this->values[] = $value;
		if (++$this->counter === $this->limit) {
			$this->flush();
		}
	}


	/**
	 * If at least one value was appended, calls callback and resets state.
	 */
	public function flush()
	{
		if ($this->counter !== 0) {
			$onFlush = $this->onFlush; // performance optimization
			$onFlush($this->values);
			$this->values = array();
			$this->counter = 0;
		}
	}


	public function offsetExists($index)
	{
		throw new NotSupportedException('Checking existence of index is not supported use callback instead.');
	}


	public function offsetGet($index)
	{
		throw new NotSupportedException('Retrieving values via index is not supported use callback instead.');
	}


	public function offsetSet($index, $value)
	{
		if ($index !== NULL) {
			throw new InvalidArgumentException('Setting values via index is not supported use append instead.');
		}

		$this->append($value);
	}


	public function offsetUnset($index)
	{
		throw new NotSupportedException('Batch is append only.');
	}

}