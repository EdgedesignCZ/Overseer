<?php

namespace Edge\Overseer;

class GrepTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider text2GrepProvider */
    public function testShouldIgnoreRegexp($ignoredExpressions, $sourceText, $expectedText)
    {
        $this->grep = new Diff($ignoredExpressions);
        $this->assertSame($expectedText, $this->grep->filterOutIgnoredLines($sourceText));
    }

    public function text2GrepProvider()
    {
        return array(
            'nothing' => array(array(), '', ''),
            'nothing to ignore' => array(array(), 'text', 'text'),
            'one expression' => array(
                array("#error#"),
                "asd
error
rre",
                "asd
rre"
            ),
            'multiple expressions ' => array(
                array("#File does not exist#", "#not found or unable to stat#"),
                "[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /thisShouldNotExist
[Fri Nov 28 08:59:20 2014] [error] [client 141.255.162.18] script '/wp-login.php' not found or unable to stat
",
                ""
            ),
        );
    }
}
