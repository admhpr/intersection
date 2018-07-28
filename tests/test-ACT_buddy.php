<?php
/**
 * Class ACFBCoreTest
 *
 * @package Acf_Buddy
 */

/**
 * Sample test case.
 */
require_once 'lib/autoload.php';

use ACFBCore\ACF_buddy;

class ACFBCoreTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}

	public function testPrepareSections(){
		$acfb = new ACF_buddy();
		$sections = $acfb->prepare_sections([], true);
		$this->assertInternalType('array', $sections);
		
		if(count($sections) > 0){
			$first=$sections[0];
			$this->assertInternalType('array', $first);
		}
	}
}
