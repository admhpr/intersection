<?php
/**
 * Class ACFTest
 *
 * @package Intersection
 */

require_once 'lib/autoload.php';

use  IntersectionHandler\ACF;

class  ACFTest extends WP_UnitTestCase {

	/***
	 * Testing mock data to enusure the process_layout function creates the correct data 
	 * structure, if this test is passing but there is still issues it is most likely from the other
	 * parts of the prepare_section function which can't be tested as it uses ACF methods
	 */

	public function testProcessLayout(){
		$acfb = new ACF();
		$phpunit = true;
		$sections = $acfb->prepare_sections([], $phpunit);
		$this->assertInternalType('array', $sections);
		
		if(count($sections) > 0){
			
			$first=$sections[0];
			$this->assertInternalType('array', $first);	
			$this->assertArrayHasKey('mock_partial', $first);

			$partial = $first['mock_partial'];
			$this->assertInternalType('array', $partial);
			$this->assertInternalType('array', $partial[0]);	

			$partial_first = $partial[0];
			$this->assertArrayHasKey('acf_fc_layout', $partial_first);
			$this->assertArrayHasKey($partial_first['acf_fc_layout'], $partial_first);

			$layout = $partial_first[$partial_first['acf_fc_layout']];
			$this->assertInternalType('array', $layout);

			$section_content_first = $layout[0];
			$this->assertArrayHasKey('acf_fc_layout', $section_content_first);
			$this->assertArrayHasKey($section_content_first['acf_fc_layout'], $section_content_first);

			$content = $section_content_first[$section_content_first['acf_fc_layout']];
			$this->assertInternalType('array', $content);
		
		}
	}
}
