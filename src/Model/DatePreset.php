<?php
namespace Model;

class DatePreset
{
    const ONE_DAY = '1d';
    const ONE_WEEK = '1w';
    const THIS_WEEK = 'tw';
    const LAST_WEEK = 'lw';
    const ONE_MONTH = '1m';
    const THIS_MONTH = 'tm';
    const LAST_MONTH = 'lm';
    const ONE_YEAR = '1y';
    const THIS_YEAR = 'ty';
    const LAST_YEAR = 'ly';
    const FOREVER = 'forever';
    const DATE_FORMAT = 'Y-m-d';

    public function getConfig()
    {
        $day = date('w');
        return [
            [
                'label' => 'Today',
                'value' => self::ONE_DAY,
                'dates' => [
                    'start' => $this->formatDate(new \DateTime()),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'One Week',
                'value' => self::ONE_WEEK,
                'dates' => [
                    'start' => $this->formatDate((new \DateTime())->modify('-1 week')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'This week',
                'value' => self::THIS_WEEK,
                'dates' => [
                    'start' => $this->formatDate((new \DateTime())->modify('-'.$day.' days')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'One Month',
                'value' => self::ONE_MONTH,
                'dates' => [
                    'start' => $this->formatDate((new \DateTime())->modify('-1 month')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'This Month',
                'value' => self::THIS_MONTH,
                'dates' => [
                    'start' => $this->formatDate(new \DateTime(date('Y').'-'.(date('m') - 1).'-01')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'Last Month',
                'value' => self::LAST_MONTH,
                'dates' => [
                    'start' => $this->formatDate((new \DateTime(date('Y').'-'.(date('m') - 1).'-01'))),
                    'end' => $this->formatDate((new \DateTime(date('Y').'-'.date('m').'-01'))->modify("-1 day"))
                ]
            ],
            [
                'label' => 'One Year',
                'value' => self::ONE_YEAR,
                'dates' => [
                    'start' => $this->formatDate((new \DateTime())->modify('-1 year')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'This Year',
                'value' => self::THIS_YEAR,
                'dates' => [
                    'start' => $this->formatDate(new \DateTime(date('Y').'-01-01')),
                    'end' => $this->formatDate(new \DateTime())
                ]
            ],
            [
                'label' => 'Last Year',
                'value' => self::LAST_YEAR,
                'dates' => [
                    'start' => $this->formatDate(new \DateTime((date('Y')-1).'-01-01')),
                    'end' => $this->formatDate(new \DateTime((date('Y')-1).'-12-31'))
                ]
            ],
            [
                'label' => 'Since the big bang',
                'value' => self::FOREVER,
                'dates' => [
                    'start' => '',
                    'end' => ''
                ]
            ],
        ];
    }

    protected function formatDate(\DateTime $date, $format = self::DATE_FORMAT)
    {
        return $date->format($format);
    }
}
