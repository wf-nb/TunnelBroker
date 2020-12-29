<?php

namespace App\Jobs;

use App\Http\Controllers\NodeController;
use App\Http\Controllers\TunnelController;
use App\Models\IPAllocation;
use App\Models\IPPool;
use App\Models\Tunnel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use IPTools\Network;

class ChangeTunnelIP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tunnel;

    /**
     * Create a new job instance.
     *
     * @param Tunnel $tunnel
     */
    public function __construct(Tunnel $tunnel)
    {
        $this->tunnel = $tunnel;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $ssh = NodeController::connect($this->tunnel->node);
        $result[] = $ssh->exec((new TunnelController())->changeTunnelCommand($this->tunnel));//执行创建Tunnel命令
        \Log::info('exec result',$result);
        $this->tunnel->update(['status'=>1]);
    }

}
