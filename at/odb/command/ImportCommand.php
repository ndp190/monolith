<?php

namespace andytruong\odb\command;

use andytruong\odb\domain\BreadRepository;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImportCommand extends Command
{
    private $client;
    private $repository;

    public function __construct(Client $client, BreadRepository $repository)
    {
        parent::__construct();

        $this->client = $client;
        $this->repository = $repository;
    }

    protected function configure()
    {
        $this->setName('odb:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = $this->client->request('GET', 'http://odb.org/2016/10/04/setting-prisoners-free/');

        $title = $crawler->filter('.entry-title')->first()->text();
        $description = $crawler->filter('.entry-content > .post-content > p')->first()->text();

        $body = [];
        $crawler
            ->filter('.entry-content > .post-content > p')
            ->nextAll()
            ->each(
                function (Crawler $node) use (&$body) {
                    if ('p' == $node->nodeName()) {
                        $html = $node->html();
                        $body[] = trim($html);
                    }
                }
            );

        $poem = $crawler->filter('.entry-content > .poem-box > p')->html();
        $thought = $crawler->filter('.entry-content > .thought-box > p')->html();

        $insight = [];
        $crawler
            ->filter('.insight-wrapper > .insight-box > p')
            ->each(
                function (Crawler $node) use (&$insight) {
                    $html = $node->html();
                    $insight[] = trim($html);
                }
            );

        $created = $crawler->filter('a.calendar-toggle')->first()->text();
        $created = strtotime($created);
        $image = $crawler->filter('.entry-thumbnail > img')->first()->attr('src');
        $audio = $crawler->filter('.download-mp3 > a')->attr('href');
        $audio = parse_url($audio)['query'];
        $audio = \GuzzleHttp\Psr7\parse_query($audio)['file'];
        $scripture = $crawler->filter('.passage-box > a')->first()->attr('href');
        $scripture = parse_url($scripture)['query'];
        $scripture = \GuzzleHttp\Psr7\parse_query($scripture)['search'];
        $plan = $crawler->filter('.passage-box > a')->first()->nextAll()->first()->attr('href');
        $plan = parse_url($plan)['query'];
        $plan = \GuzzleHttp\Psr7\parse_query($plan)['search'];
        $plan = explode('; ', $plan);
        $author = $crawler->filter('.insight-wrapper .entry-meta-box .author > .vcard > a')->text();
        $tags = [];
        $crawler
            ->filter('.entry-footer > .post-tags > a')
            ->each(
                function (Crawler $node) use (&$tags) {
                    $tags[] = trim($node->text());
                }
            );

        $this->save($title, $description, $body, $created, $image, $audio, $scripture, $plan, $author, $tags);
    }

    private function save($title, $description, $body, $created, $image, $audio, $scripture, array $plan, $author, array $tags)
    {
        $id = $this->repository->save($title, $description, $body, $created);
        $this
            ->repository
            ->linkImage($id, $image)
            ->linkAudio($id, $audio)
            ->linkScripture($id, $scripture)
            ->linkPlan($id, $plan)
            ->linkAuthor($id, $author)
            ->linkTags($id, $tags);

        dump(
            $this->repository->load($id)
        );
    }
}
