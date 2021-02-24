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

	namespace Credoteam\Credo\Utility;

	class Http
	{
		public static function redirect( $location, $replace = true, $httpResponseCode = null )
		{
			// do a redirect
			header( 'Location: ' . $location, $replace, $httpResponseCode );
		}

	}
