<?php
namespace common\components\bookmaker\parimatch\factories\results;

use phpQuery;
/**
 * Created by PhpStorm.
 */
class Football extends Base
{
    public function getData()
    {
        $FootballResult = new \common\sport\result\Football();

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
                        $FootballResult
                            ->setIsEmpty(false)
                            ->setIsCancel(true);
                        return $FootballResult;
                    }


                    if(preg_match('/(\d+):(\d+).+?(\d+):(\d+)/ui', $result, $out)) {
                        $FootballResult
                            ->setIsEmpty(false)
                            ->setAttribute('team_1_part_1', (int)$out[3])
                            ->setAttribute('team_2_part_1', (int)$out[4])
                            ->setAttribute('team_1_part_2', (int)($out[1] - $out[3]))
                            ->setAttribute('team_2_part_2', (int)($out[2] - $out[4]))
                            ->setTeam1Result((int)$out[1])
                            ->setTeam2Result((int)$out[2]);
                        return $FootballResult;
                    }

                    if(preg_match('/(\d+):(\d+)/ui', $result, $out)) {
                        $FootballResult
                            ->setIsEmpty(false)
                            ->setTeam1Result((int)$out[1])
                            ->setTeam2Result((int)$out[2]);
                        return $FootballResult;
                    }

                    return $FootballResult;
                }
            }
        }

        return $FootballResult;
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
        $returned = parent::prepareTeams();

        foreach ($returned as $team) {
            if(preg_match('/угл\./ui', $team)) {
                $returned[] = str_replace('угл.', 'угловые', $team);
            }

            if(preg_match('/ж\/к/ui', $team)) {
                $returned[] = str_replace('ж/к', 'желтые карточки', $team);
            }
        }

        $this->setTeamList($returned);

        return $returned;
    }
}