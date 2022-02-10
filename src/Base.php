<?php
/**
 *
 * Description
 *
 * @package        Credo
 * @category       Source
 * @author         Credo Team <credoteam@credo.com>
 * @date           2020-11-06
 * @copyright (c)  2020, CREDO (http://www.credocentral.com)
 *
 */

	namespace Credoteam\Credo;

	use Credoteam\Credo\CURL;
	use Credoteam\Credo\Utility\Text;

	class Base
	{

		private
			$_apiBaseUrl = 'https://api.credocentral.com/credo-payment/v1/', // with trailing slash
			$_curl,
			$_secretKey,
			$_endPoint,

			/*Getting Error Infomation*/
			$_errorMessages = [];

		public
			$resource,
			$action,
			$args,
			$data,
			// response from the endpoint
			$response;

		public function __construct( $secretKey )
		{
			// save key in memory
			$this->_secretKey = $secretKey;

			return $this;
		}

		public function setResource( $resource )
		{
			$this->resource = $resource;

			return $this;
		}

		public function setAction( $action, array $args = [] )
		{
			if ( ! is_array( $args ) ) {
				throw new \Exception( 'Action arguments can only be of datatype Array' );
			}

			$this->action = $action;
			$this->args   = $args;

			return $this;
		}

		/**
		 * Initiate Request to the paystack RESTful API and return response Obj
		 *
		 * @param array  $withData
		 * @param string $requestMethod
		 * @param bool   $returnArray set to true to return response as associate array
		 *
		 * @todo Utilize the third argument..
		 *
		 * @return mixed
		 * @throws \Exception
		 */
		public function sendRequest( array $withData = [], $requestMethod = 'POST', $returnArray = false )
		{
			if ( ! is_array( $withData ) ) {
				throw new \Exception( 'sendRequest arguments can only be of datatype Array' );
			}

			$this->data = $withData;

			$this->_endPoint = $this->_apiBaseUrl .
			                   Text::removeSlashes( $this->resource ) . '/' .
			                   Text::removeSlashes( $this->action );
			// append parameters to endPoint
			if ( count( $this->args ) > 0 ) {
				$this->_endPoint .= '/' . implode( '/', $this->args );
			}

			// send the request and return result as json object
			$this->_curl =
				( new CURL(
					$this->_endPoint,
					$requestMethod ) )
					->setRequestHeader( 'Authorization', 'Bearer ' . $this->_secretKey );

			$this->response =
				json_decode(
					$this->_curl
						->run( $this->data, 'json' ) );

			return $this->response;
		}

		/**
		 * @return mixed
		 */
		public function getEndPoint()
		{
			// this works only after executing sendRequest
			return $this->_endPoint;
		}

		/**
		 * @param mixed $errorMessages
		 */
		public function setErrorMessages( $errorMessages )
		{
			//if errorMessages is string
			if ( is_string( $errorMessages ) ) {
				$this->_errorMessages[] = $errorMessages;
			}
			//if errorMessages is array
			if ( is_array( $errorMessages ) ) {
				$this->_errorMessages = array_merge( $this->_errorMessages, $errorMessages );
			}
		}

		/**
		 * @param bool   $toString
		 * @param string $delimiter
		 *
		 * @return array|string
		 */
		public function getErrorMessages( $toString = false, $delimiter = '<br>' )
		{
			$errorMessages = $this->_errorMessages;
			if ( $toString ) {
				// return errorMessage as String
				unset( $errorMessages ); //to avoid datatype conflict
				$errorMessages = join( $delimiter, $this->_errorMessages );
			}

			return $errorMessages;
		}


	}
