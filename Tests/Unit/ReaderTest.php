<?php
namespace Econic\CsvReader\Tests\Unit;

class CsvReaderTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var \Econic\CsvReader\CsvReader
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Econic\CsvReader\Reader();
	}

	public function tearDown() {
		unset($this->fixture);
	}
	
	/**
	 * @test
	 */
	public function sourceFunctions() {
		$this->assertEquals(
			'',
			$this->fixture->getSource()
		);
		$this->assertEquals(
			'!',
			$this->fixture->setSource('!')->getSource()
		);
	}
	
	/**
	 * @test
	 */
	public function sourceHasHeadlineFunctions() {
		$this->assertEquals(
			false,
			$this->fixture->getSourceHasHeadline()
		);
		$this->assertEquals(
			true,
			$this->fixture->setSourceHasHeadline(true)->getSourceHasHeadline()
		);
	}
	
	/**
	 * @test
	 */
	public function delimiterFunctions() {
		$this->assertEquals(
			',',
			$this->fixture->getDelimiter()
		);
		$this->assertEquals(
			'!',
			$this->fixture
				->setDelimiter('!')
				->getDelimiter()
		);
	}
	
	/**
	 * @test
	 */
	public function newlineFunctions() {
		$this->assertEquals(
			"\n",
			$this->fixture->getNewline()
		);
		$this->assertEquals(
			'!',
			$this->fixture
				->setNewline('!')
				->getNewline()
		);
	}
	
	/**
	 * @test
	 */
	public function enclosureFunctions() {
		$this->assertEquals(
			'\'',
			$this->fixture->getEnclosure()
		);
		$this->assertEquals(
			'!',
			$this->fixture
				->setEnclosure('!')
				->getEnclosure()
		);
	}
	
	/**
	 * @test
	 */
	public function escapeFunctions() {
		$this->assertEquals(
			'\\',
			$this->fixture->getEscape()
		);
		$this->assertEquals(
			'!',
			$this->fixture
				->setEscape('!')
				->getEscape()
		);
	}
	
	/**
	 * @test
	 */
	public function trimFunctions() {
		$this->assertEquals(
			true,
			$this->fixture->getTrim()
		);
		$this->assertEquals(
			false,
			$this->fixture
				->setTrim(false)
				->getTrim()
		);
	}
	
	/**
	 * @test
	 */
	public function setKey() {
		$this->assertEquals(
			array(),
			$this->fixture->getKeys()
		);
		$keys = array(
				0 => 15,
				3 => "drei"
			);
		$this->assertEquals(
			$keys,
			$this->fixture
				->setKey(0, "15")
				->setKey(3, "drei")
				->getKeys()
		);
	}
	
	/**
	 * @test
	 */
	public function addKeys() {
		$this->assertEquals(
			array(),
			$this->fixture->getKeys()
		);
		$keys = array(
				0 => 15,
				3 => "drei"
			);
		$this->assertEquals(
			$keys,
			$this->fixture->addKeys($keys)->getKeys()
		);
	}
	
	/**
	 * @test
	 */
	public function resetKeys() {
		$this->assertEquals(
			array(),
			$this->fixture->getKeys()
		);
		$keys = array(
				0 => "15",
				1 => "drei"
			);
		$this->assertEquals(
			$keys,
			$this->fixture->addKeys($keys)->getKeys()
		);
		$this->assertEquals(
			array(),
			$this->fixture->resetKeys()->getKeys()
		);
	}
	
	/**
	 * @test
	 */
	public function addKeysOverridesKeysOnlyWhenNecessary() {
		$overrideKeys = array(
			1 => 'zwei',
			2 => "drei"
		);
		$this->assertEquals(
			array(
				0 => 'eins',
				1 => 'zwei',
				2 => 'drei'
			),
			$this->fixture
				->setKey(0, 'eins')
				->setKey(1, 'falsch')
				->addKeys($overrideKeys)
				->getKeys()
		);
	}
	
	/**
	 * @test
	 */
	public function addSourceWithHeadline() {
		$sourceWithHeadline = "A,B,C\none,two,three\neins,zwei,drei\nun,dos,tres";
		$expectedResult = array(
			'0' => array(
				'A' => 'one',
				'B' => 'two',
				'C' => 'three'
			),
			'1' => array(
				'A' => 'eins',
				'B' => 'zwei',
				'C' => 'drei'
			),
			'2' => array(
				'A' => 'un',
				'B' => 'dos',
				'C' => 'tres'
			)
		);
		$this->assertEquals(
			$expectedResult,
			$this->fixture
				->setSource($sourceWithHeadline)
				->setSourceHasHeadline(true)
				->parse()
		);
	}
	
	/**
	 * @test
	 */
	public function modifierFunctions() {
		$this->assertEquals(
			array(),
			$this->fixture->getModifiers()
		);

		$f1 = function($var){
			return "-".$var."-";
		};
		$f2 = function($var){
			return strtolower($var);
		};
		$modifiers = array(
			0 => array(
				0 => $f1
			),
			'drei' => array(
				0 => $f2
			)
		);

		$this->assertEquals(
			$modifiers,
			$this->fixture
				->addModifier(0, $f1)
				->addModifier('drei', $f2)
				->getModifiers()
		);
	}
	
	/**
	 * @test
	 */
	public function resetModifiersWithoutKeyGiven() {
		$this->assertEquals(
			array(),
			$this->fixture->getModifiers()
		);

		$f1 = function($var){
			return "-".$var."-";
		};
		$f2 = function($var){
			return strtolower($var);
		};
		$modifiers = array(
			0 => array(
				0 => $f1
			),
			'drei' => array(
				0 => $f2
			)
		);

		$this->assertEquals(
			$modifiers,
			$this->fixture
				->addModifier(0, $f1)
				->addModifier('drei', $f2)
				->getModifiers()
		);

		$this->assertEquals(
			array(),
			$this->fixture->resetModifiers()->getModifiers()
		);

	}
	
	/**
	 * @test
	 */
	public function resetModifiersWithKeyGiven() {
		$this->assertEquals(
			array(),
			$this->fixture->getModifiers()
		);

		$f1 = function($var){
			return "-".$var."-";
		};
		$f2 = function($var){
			return strtolower($var);
		};
		$modifiers = array(
			0 => array(
				0 => $f1
			),
			'drei' => array(
				0 => $f2
			)
		);

		$this->assertEquals(
			$modifiers,
			$this->fixture
				->addModifier(0, $f1)
				->addModifier('drei', $f2)
				->getModifiers()
		);

		unset($modifiers["drei"]);

		$this->assertEquals(
			$modifiers,
			$this->fixture->resetModifiers("drei")->getModifiers()
		);

	}
	
	/**
	 * @test
	 */
	public function trimRespectation() {
		$source = "one ,two,three \n	 	eins, zwei,drei\nun , dos, tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => 'two',
				'2' => 'three'
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setTrim(true)
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function delimiterRespectation() {
		$source = "one!two!three\neins!zwei!drei\nun!dos!tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => 'two',
				'2' => 'three'
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setDelimiter('!')
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function newlineRespectation() {
		$source = "one,two,three!eins,zwei,drei!un,dos,tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => 'two',
				'2' => 'three'
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setNewline('!')
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function enclosureRespectation() {
		$source = "one,two,'three,four'\neins,zwei,drei\nun,dos,tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => 'two',
				'2' => 'three,four'
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setEnclosure("'")
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function escapeRespectation() {
		$source = "one,t'w'o,'three is -'a-' number'\neins,zwei,drei\nun,dos,tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => "t'w'o",
				'2' => "three is 'a' number"
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setEscape("-")
				->setEnclosure("'")
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function keyRespectation() {
		$source = "one,two,three\neins,zwei,drei\nun,dos,tres";
		$expectedResult = array(
			'0' => array(
				'a' => 'one',
				'bb' => 'two',
				'ccc' => 'three'
			),
			'1' => array(
				'a' => 'eins',
				'bb' => 'zwei',
				'ccc' => 'drei'
			),
			'2' => array(
				'a' => 'un',
				'bb' => 'dos',
				'ccc' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setKey(0, "a")
				->setKey(1, "bb")
				->setKey(2, "ccc")
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function modifierRespectation() {
		$source = "OnE,two,three\neIns,zwei,drei\nUN,dos,trES";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => '-two-',
				'2' => 'THREE'
			),
			'1' => array(
				'0' => 'eins',
				'1' => '-zwei-',
				'2' => 'DREI'
			),
			'2' => array(
				'0' => 'un',
				'1' => '-dos-',
				'2' => 'TRES'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->addModifier(
					0,
					function($val){
						return strtolower($val);
					}
				)
				->addModifier(
					1,
					function($val){
						return "-".$val;
					}
				)
				->addModifier(
					1,
					function($val){
						return $val."-";
					}
				)
				->addModifier(
					2,
					function($val){
						return strtoupper($val);
					}
				)
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function modifierAndKeyRespectation() {
		$source = "OnE,two,three\neIns,zwei,drei\nUN,dos,trES";
		$expectedResult = array(
			'0' => array(
				'a' => 'one',
				'bb' => '-two-',
				'ccc' => 'THREE'
			),
			'1' => array(
				'a' => 'eins',
				'bb' => '-zwei-',
				'ccc' => 'DREI'
			),
			'2' => array(
				'a' => 'un',
				'bb' => '-dos-',
				'ccc' => 'TRES'
			)
		);
		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->addKeys(array(
					0 => 'a',
					1 => 'bb',
					2 => 'ccc'
				))
				->addModifier(
					'a',
					function($val){
						return strtolower($val);
					}
				)
				->addModifier(
					'bb',
					function($val){
						return "-".$val."-";
					}
				)
				->addModifier(
					'ccc',
					function($val){
						return strtoupper($val);
					}
				)
				->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function defaultRunWithSourceOnly() {
		$source = "one,two,three\neins,zwei,drei\nun,dos,tres";
		$expectedResult = array(
			'0' => array(
				'0' => 'one',
				'1' => 'two',
				'2' => 'three'
			),
			'1' => array(
				'0' => 'eins',
				'1' => 'zwei',
				'2' => 'drei'
			),
			'2' => array(
				'0' => 'un',
				'1' => 'dos',
				'2' => 'tres'
			)
		);
		$this->assertEquals(
			$this->fixture->setSource($source)->parse(),
			$expectedResult
		);
	}
	
	/**
	 * @test
	 */
	public function fullConfigurationRunWithSourceDelimiterNewlineEnclosureEscapeKeysTrimAndModifiers() {
		$source = " ONE-% -TWO%- THREE!Eins -%Zwei -% %-Drei !un-d o s-tres";
		$delimiter = "-";
		$newline = "!";
		$enclosure = "%";
		$escape = "-";
		$keys = array(
			0 => "first",
			1 => "second",
			2 => "third",
		);
		$trim = false;
		$modifiers = array(
			"first" => function($val){
				return strtolower($val);
			},
			"second" => function($val){
				return trim($val);
			},
			"third" => function($val){
				return strtoupper($val);
			},
		);
		$expectedResult = array(
			'0' => array(
				'first' => ' one',
				'second' => '-TWO',
				'third' => ' THREE'
			),
			'1' => array(
				'first' => 'eins ',
				'second' => 'Zwei %',
				'third' => 'DREI '
			),
			'2' => array(
				'first' => 'un',
				'second' => 'd o s',
				'third' => 'TRES'
			)
		);

		$this->assertEquals(
			$this->fixture
				->setSource($source)
				->setDelimiter($delimiter)
				->setNewline($newline)
				->setEnclosure($enclosure)
				->setEscape($escape)
				->setTrim($trim)
				->addKeys($keys)
				->addModifier(
					'first',
					$modifiers["first"]
				)
				->addModifier(
					'second',
					$modifiers["second"]
				)
				->addModifier(
					'third',
					$modifiers["third"]
				)
				->parse(),
			$expectedResult
		);
	}

}
?>