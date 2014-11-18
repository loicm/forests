<?php
namespace loicm\Forests\Content;

use loicm\Forests\Config;


class Note extends Base
{
    protected $type = 'note';

    protected $note_dir = '';
    protected $note_file = '';
    protected $note_pathinfo = '';
    
    protected $note_date = '';
    protected $note_date_human = '';

    protected $path_parts = array();


    public function __construct(Config $config, $note_file)
    {
        parent::__construct($config, $note_file);

        $path_parts = pathinfo($this->source_file);
        list($year, $month, $day) = explode(
            '/',
            preg_replace(
                '/^.+\/notes\//',
                '',
                $path_parts['dirname']
            )
        );
        $this->path_parts = array(
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'filename' => $path_parts['filename']
        );

        $slug = 'notes/'.$year.'/'.$path_parts['filename'];
        $this->note_pathinfo = $this->config->base_path.$slug;
        $this->output_content_dir = $this->config->output_dir.$slug;
        $this->output_file = $this->output_content_dir.'/index.html';
        
        $this->note_date = $year.'-'.$month.'-'.$day;
        $datetime = new \DateTime($this->note_date);
        $dfmt = new \IntlDateFormatter(
            'fr-FR',
            \IntlDateFormatter::LONG,
            \IntlDateFormatter::NONE,
            'Europe/Paris'
        );
        $this->note_date_human = $dfmt->format($datetime);
        $this->note_date_human_short = preg_replace('/ (\d{4})$/', '', $dfmt->format($datetime));

        $this->tpl_vars['page']['date']['full'] = $this->note_date_human;
        $this->tpl_vars['page']['date']['short'] = $this->note_date_human_short;
        $this->tpl_vars['page']['date']['datetime'] = date("Y-m-d H:i:s", filemtime($this->source_file));
    }

    public function getURL()
    {
        return $this->note_pathinfo;
    }

    public function getAbsoluteURL()
    {
        return $this->config->blog_url.$this->note_pathinfo;
    }

    public function getTitle()
    {
        return $this->metadata->title;
    }

    public function getDate($type = 'short')
    {
        if ($type == 'short') return $this->note_date_human_short;
        return $this->note_date_human;
    }

    public function getFeedDate()
    {
        list($year, $month, $day) = explode('-', $this->note_date);
        return mktime(0, 0, 0, $month, $day, $year);
    }
}