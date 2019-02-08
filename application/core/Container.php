<?php

/**
* ArrayAndObjectAccess
* Yes you can access class as array and the same time as object
*
* @author Yousef Ismaeil <cliprz@gmail.com>
*/

class Container implements ArrayAccess {

    /**
     * Instances
     *
     * @var array
     * @access private
     */
    private $instances = [];

    /**
     * Get a instances by key
     *
     * @param string The key instances to retrieve
     * @access public
     */
    public function &__get ($key) {
        return $this->instances[$key];
    }

    /**
     * Assigns a value to the specified instances
     * 
     * @param string The instances key to assign the value to
     * @param mixed  The value to set
     * @access public 
     */
    public function __set($key,$value) {
        $this->instances[$key] = $value;
    }

    /**
     * Whether or not an instances exists by key
     *
     * @param string An instances key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset ($key) {
        return isset($this->instances[$key]);
    }

    /**
     * Unsets an instances by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key) {
        unset($this->instances[$key]);
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset,$value) {
        if (is_null($offset)) {
            $this->instances[] = $value;
        } else {
            $this->instances[$offset] = $value;
        }
    }

    /**
     * Whether or not an offset exists
     *
     * @param string An offset to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function offsetExists($offset) {
        return isset($this->instances[$offset]);
    }

    /**
     * Unsets an offset
     *
     * @param string The offset to unset
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset($this->instances[$offset]);
        }
    }

    /**
     * Returns the value at specified offset
     *
     * @param string The offset to retrieve
     * @access public
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->instances[$offset] : null;
    }

}
