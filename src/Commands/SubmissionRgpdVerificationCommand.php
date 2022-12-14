<?php

namespace Kiwilan\Steward\Commands;

use Illuminate\Console\Command;
use Kiwilan\Steward\Models\Submission;

class SubmissionRgpdVerificationCommand extends CommandSteward
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:rgpd-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old submissions that are not accepted the RGPD';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->title();

        $submissions = Submission::whereYear('created_at', '<', date('Y') - 4)->get();
        $submissions->each(fn (Submission $submission) => $submission->delete());

        $this->info("{$submissions->count()} submissions deleted");

        return Command::SUCCESS;
    }
}
