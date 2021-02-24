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


	class Debug
	{
		private
		static
			$openTag = '<pre style="overflow: auto; max-height: 70%; max-width: 95%; position: fixed; z-index: 9999; left: 15px; top: 15px; padding: 15px; background-color: #fcfcfc; border: solid 1px #aaa; line-height: 1.1rem;">',
			$closeTag = '</pre>';

		public static function printStr( $value )
		{
			echo self::$openTag .
			     $value .
			     self::$closeTag;
		}

		public static function print_r( $value )
		{
			echo self::$openTag .
			     print_r( $value, true ) .
			     self::$closeTag;
		}

		public static function var_dump( $value )
		{
			echo self::$openTag;
			var_dump( $value );
			echo self::$closeTag;
		}
	}
