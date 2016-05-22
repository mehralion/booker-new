<?php
namespace common\components\bookmaker\parimatch\factories\results;

use phpQuery;
/**
 * Created by PhpStorm.
 */
class Tennis extends Base
{
    public function getData()
    {
        $Result = new \common\sport\result\Tennis();

        $findBody = $this->getListData();
        if ($findBody->count()) {
            foreach ($findBody as $temp) {
                $temp = phpQuery::pq($temp);
                $tr = $temp->parent('tr');
                if ($tr->count()) {
                    $team_1 = $this->prepareTeam($tr->find('td')->eq(1)->text());
                    $team_2 = $this->prepareTeam($tr->find('td')->eq(2)->text());
                    if(!$this->hasTeam($team_1) || !$this->hasTeam($team_2))
                      continue;

                    $result = trim(strip_tags($tr->find('td')->eq(3)->text()), ',. ');
                    if(preg_match('/не состоялся|не состоялся|матч отменен/ui', $result)) {
                        $Result->setIsCancel(true)
                            ->setIsEmpty(false);
                        return $Result;
                    }

                    if(preg_match('/^(\d+):(\d+)/ui', $result, $out) && preg_match('/\((.+?)\)/ui', $result, $out2)) {
                        $set_list = explode(',', $out2[1]);

                        $setSum = $out[1] + $out[2];
                        if($setSum != count($set_list))
                            continue;

                        $Result
                            ->setIsEmpty(false)
                            ->setTeam1Result((int)$out[1])
                            ->setTeam2Result((int)$out[2]);

                        foreach ($set_list as $key => $res) {
                            if(preg_match('/(\d+):(\d+)/ui', $res, $_out)) {
                                $Result->setAttribute(sprintf('team_1_part_%d', $key + 1), (int)$_out[1]);
                                $Result->setAttribute(sprintf('team_2_part_%d', $key + 1), (int)$_out[2]);
                            }
                        }
                    }

                    return $Result;
                }
            }
        }

        return $Result;
    }

    /**
     * @return \phpQueryObject
     */
    protected function getListData()
    {
        $teamList = $this->getTeamList();
        return $this->getDom()->find('form td')->filter(function($i, $node) use ($teamList) {
            $array = array_map(function($v){ return str_replace(['/'], ['\/'], addslashes($v)); }, $teamList);
            return preg_match('/'.implode('|', $array).'/ui', $node->nodeValue);
        });
    }

    protected function prepareTeams()
    {
        return parent::prepareTeams();
    }
}