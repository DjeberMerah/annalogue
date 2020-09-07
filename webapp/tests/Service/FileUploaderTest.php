<?php

namespace App\Tests\Service;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends KernelTestCase
{
    private $fileUploader;

    /** @var string $documentsDirectory */
    private $documentsDirectory;

    /** @var string $filename */
    private $filename;

    protected function setUp()
    {
        parent::setUp();
        
        self::bootKernel();

        $this->fileUploader = self::$container->get(FileUploader::class);

        $this->documentsDirectory = self::$container->getParameter('documents_directory');

        copy('tests/Service/file.pdf', 'tests/Service/tmp.pdf');
    }

    protected function tearDown()
    {
        unlink($this->getPath());

        parent::tearDown();
    }

    public function testUpload()
    {
        $file = new UploadedFile('tests/Service/tmp.pdf', 'tmp.pdf', 'application/pdf', null, true);

        $this->filename = $this->fileUploader->upload($file, '12345678');

        $this->assertNotNull($this->filename);
        $this->assertEquals('tmp-12345678.pdf', $this->filename);
        $this->assertFileExists($this->getPath());
    }

    private function getPath()
    {
        return $this->documentsDirectory . '/' . $this->filename;
    }
}
