<?php

/*
 * This file is part of angelitolm/happor.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Happor\Builder;

use Composer\Composer;
use Composer\IO\NullIO;
use Composer\Json\JsonFile;
use Composer\Package\Package;
use Composer\Package\CompletePackage;
use Composer\Config as ComposerConfig;
use Composer\Config\JsonConfigSource;
use Composer\Downloader\DownloadManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Michael Lee <michael.lee@zerustech.com>
 */
class ArchiveBuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $composer;

    protected $output;

    protected $input;

    protected $outputDir;

    protected $happorConfig;

    protected $root;

    protected $home;

    protected $target;

    public function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->root);
    }

    public function setUp()
    {
        $this->root = __DIR__ . '/vfs';
        $this->home = $this->root . '/home/ubuntu';
        $this->target = $this->home . '/happor.server/dist/monolog/monolog';

        $composerConfig = new ComposerConfig(true, $this->home . '/happor.server');
        $composerConfig->merge([
            'cache-dir'=> $this->home . '/.cache/composer',
            'data-dir'=> $this->home . '/.local/share/composer',
            'home'=> $this->home . '/.config/composer'
        ]);
        $composerConfig->setConfigSource(new JsonConfigSource(new JsonFile($this->home . '/.config/composer/config.json')));
        $composerConfig->setAuthConfigSource(new JsonConfigSource(new JsonFile($this->home . '/.config/composer/auth.json')));

        $downloadManager = $this->getMockBuilder('Composer\Downloader\DownloadManager')->disableOriginalConstructor()->getMock();
        $downloadManager->method('download')->will(
            $this->returnCallback(
                function($package, $source) {
                    $filesystem = new \Symfony\Component\Filesystem\Filesystem();
                    $filesystem->dumpFile(realpath($source) . '/' . 'README.md', '# The demo archive.');
                }
            )
        );

        $this->composer = new Composer();
        $this->composer->setConfig($composerConfig);
        $this->composer->setDownloadManager($downloadManager);

        $this->input = $this->getMockBuilder('Symfony\Component\Console\Input\InputInterface')->disableOriginalConstructor()->getMock();
        $this->input->method('getOption')->with('stats')->willReturn($this->returnValue(false));

        $this->output = new NullOutput();

        $this->outputDir = $this->home . '/happor.server';

        $this->happorConfig = [
            "name" => "monolog/monolog",
            "homepage" => "https://github.com/Seldaek/monolog.git",
            "repositories" => [
                [ "type" => "composer", "url" => "https://packagist.org" ]
            ],
            "require" => [
                "monolog/monolog" => "1.13.0"
            ],
            "config" => [
                "secure-http" => false
            ],
            "require-dependencies" => false,
            "require-dev-dependencies" => false,
            'archive' => [
                'directory' => 'dist',
                'format' => 'tar',
                'prefix-url' => 'http://happor.localhost:4680',
                'skip-dev' => false
            ]
        ];
    }

    /**
     * @dataProvider getDataForTestDump
     */
    public function testDumpWithDownloadedArchives($customConfig, $packages, $expectedFileName)
    {
        $this->initArchives();

        $config = array_merge_recursive($this->happorConfig, $customConfig);

        $builder = new ArchiveBuilder($this->output, $this->outputDir, $config, true);
        $builder->setInput($this->input);
        $builder->setComposer($this->composer);
        $builder->dump($packages);

        $this->assertSame($expectedFileName, basename($packages[0]->getDistUrl()));
    }

    private function getPackages()
    {
        $package = new CompletePackage('monolog/monolog', '1.13.0.0', '1.13.0');
        $package->setId(9);
        $package->setType('library');
        $package->setSourceType('git');
        $package->setSourceUrl('https://github.com/Seldaek/monolog.git');
        $package->setSourceReference('c41c218e239b50446fd883acb1ecfd4b770caeae');
        $package->setDistType('zip');
        $package->setDistUrl('https://api.github.com/repos/Seldaek/monolog/zipball/');
        $package->setDistReference('c41c218e239b50446fd883acb1ecfd4b770caeae');
        $package->setReleaseDate(new \DateTime("2015-03-05T01:12:12+00:00"));
        $package->setExtra(
            [
                'branch-alias' => [
                    'dev-master' => '1.13.x-dev'
                ]
            ]
        );
        $package->setNotificationUrl('https://packagist.org/downloads/');
        return [$package];
    }

    public function getDataForTestDump()
    {
        return [
            [[], $this->getPackages(), 'monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-zip-d4a976.tar'],
            [['archive' => ['override-dist-type' => false]], $this->getPackages(), 'monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-zip-d4a976.tar'],
            [['archive' => ['override-dist-type' => true]], $this->getPackages(), 'monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-tar-d4a976.tar']
        ];
    }

    /**
     * @dataProvider getDataForTestDump
     */
    public function testDumpWithoutDownloadedArchives($customConfig, $packages, $expectedFileName)
    {
        $this->removeArchives();

        $config = array_merge_recursive($this->happorConfig, $customConfig);
        $builder = new ArchiveBuilder($this->output, $this->outputDir, $config, true);
        $builder->setInput($this->input);
        $builder->setComposer($this->composer);
        $builder->dump($packages);

        $this->assertSame($expectedFileName, basename($packages[0]->getDistUrl()));
    }

    private function initArchives()
    {
        $fs = new Filesystem();
        $fs->mkdir($this->target, 0777);
        $fs->dumpFile($this->target . '/monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-zip-d4a976.tar', 'the package archive.');
        $fs->dumpFile($this->target . '/monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-tar-d4a976.tar', 'the package archive.');
    }

    private function removeArchives()
    {
        $fs = new Filesystem();
        $fs->remove($this->target . '/monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-zip-d4a976.tar');
        $fs->remove($this->target . '/monolog-monolog-c41c218e239b50446fd883acb1ecfd4b770caeae-tar-d4a976.tar');
    }
}
