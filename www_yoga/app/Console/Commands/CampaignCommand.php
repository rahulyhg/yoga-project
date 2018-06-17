<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Model\CampaignModel;

class CampaignCommand extends Command {

	const CAMPAIGN_STATUS_IS_NOT_PUBLISH = 0;
	const CAMPAIGN_STATUS_IS_PUBLISH = 1;
	const CAMPAIGN_STATUS_IS_START = 2;
	const CAMPAIGN_STATUS_IS_FINISH = 3;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'campaign';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'campaign command';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->setNeedStartCampaign();
		$this->setNeedEndCampaign();
	}

	/**
	 * 获取未开始但是需要开始的活动
	 */
	public function setNeedStartCampaign(){
		$campaignList = CampaignModel::getNeedStartCampaign();

		$this->setCampaignStatus($campaignList, self::CAMPAIGN_STATUS_IS_START);

	}

	/**
	 * 获取需要结束的活动
	 */
	public function setNeedEndCampaign(){
		$campaignList = CampaignModel::getNeedEndCampaign();
		$this->setCampaignStatus($campaignList, self::CAMPAIGN_STATUS_IS_FINISH);
	}

	/**
	 * 批量操作活动状态
	 * @param $status
	 */
	public function setCampaignStatus($campaignList, $status) {
		if(!empty($campaignList)) {
			$campaignId = array();

			foreach ($campaignList as $campaign) {
				array_push($campaignId, $campaign->Id);
			}


			CampaignModel::setCampaignStatus($campaignId, $status);
		}
	}

}