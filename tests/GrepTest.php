<?php

namespace Edge\Overseer;

class GrepTest extends \PHPUnit_Framework_TestCase
{

    private $grep;

    public function setUp()
    {
        $this->grep = new Grep();
    }

    /**
     * @dataProvider text2GrepProvider
     */
    public function testGrepText($regexp, $sourceText, $expectedText)
    {
        $this->assertSame($expectedText, $this->grep->invertGrep($regexp, $sourceText));
    }


    public function text2GrepProvider()
    {
        return array(
            array(
                "#error#",
                "asd
error
rre",
                "asd
rre"
            ),
            array(
                "#File does not exist#",
                "[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /home/edge/WWW/www.gosms.cz/thisShouldNotExist
[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /home/edge/WWW/www.gosms.cz/thisShouldNotExist
[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /home/edge/WWW/www.gosms.cz/thisShouldNotExist
[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /home/edge/WWW/www.gosms.cz/thisShouldNotExist
[Fri Oct 24 08:38:20 2014] [error] [client 217.31.55.75] File does not exist: /home/edge/WWW/www.gosms.cz/thisShouldNotExist
",
                ""
            ),
        );
    }
}
