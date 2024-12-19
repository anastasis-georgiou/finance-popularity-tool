<?php

namespace App\Console\Commands;

use App\Jobs\CrawlTweetsJob;
use App\Models\Handle;
use Illuminate\Console\Command;

class DispatchCrawlJobs extends Command
{
    protected $signature = 'crawl:tweets';
    protected $description = 'Dispatch jobs to crawl tweets for all handles';

    public function handle()
    {
        $handles = Handle::all();

        foreach ($handles as $handle) {
            if ($this->shouldCrawl($handle)) {
                CrawlTweetsJob::dispatch($handle);
                $this->info("Dispatched crawl job for handle: {$handle->handle}");
            }
        }

        $this->info('Crawl jobs dispatched successfully.');
    }

    private function shouldCrawl(Handle $handle)
    {
        if (!$handle->last_crawled_at) {
            return true;
        }

        $nextCrawlTime = $handle->last_crawled_at->addSeconds($handle->crawling_freq);
        return now()->greaterThanOrEqualTo($nextCrawlTime);
    }
}
