<?php

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseDriver;

/**
 * Jpdfgenerator plugin.
 *
 * @package  pdfrealty
 * @since    1.0
 */
class plgSystemJpdfgenerator extends CMSPlugin
{

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;


	public function onAjaxJpdfgenerator()
    {
        $app = Factory::getApplication();
        $request_data = $app->input->getArray();
        $html = '';
        $action = 'stream';
        $template_name = 'default';
        $template_path_default = JPATH_ROOT . DS . implode(DS, ['plugins', 'system', 'jpdfgenerator', 'tmpl']);
        $template_path_theme = JPATH_ROOT . DS . 'templates' . DS . $app->getTemplate() . DS . implode(DS, ['html', 'plg_system_jpdfgenerator']);

        //проверяем есть ли кастомный шаблон в запросе
        if(isset($request_data['template']))
        {
            $template_name = $request_data['template'];
        }

        //проверяем есть ли действие в запросе
        if(isset($request_data['action']))
        {
            $action = $request_data['action'];
        }

        //проверяем если ли кастомный шаблон
        if(file_exists($template_path_theme . DS . $template_name))
        {
            $template = new FileLayout('template', $template_path_theme . DS . $template_name);

            if(!file_exists($template_path_theme . DS . $template_name . DS . 'data.php'))
            {
                echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_DATA_NOT_FOUND');
                $app->close();
            }

            $template_data = include $template_path_theme . DS . $template_name . DS . 'data.php';
            $html = $template->render($template_data);
        }
        else
        {

            //провереяем есть ли стандартные шаблоны
            if(file_exists($template_path_default . DS . $template_name))
            {
                $template = new FileLayout('template', $template_path_default . DS . $template_name);

                if(!file_exists($template_path_default . DS . $template_name . DS . 'data.php'))
                {
                    echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_DATA_NOT_FOUND');
                    $app->close();
                }

                $template_data = include $template_path_default . DS . $template_name . DS . 'data.php';
                $html = $template->render($template_data);
            }
            else
            {
                //выкидываем ошибку, если шаблон вообще не найден
                echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_NOT_FOUND');
                $app->close();
            }

        }

        JLoader::register('JMpdf', JPATH_LIBRARIES . '/mpdf/jmpdf.php');
        $pdf = new JMpdf($html);

        if($action === 'stream')
        {
            $pdf->stream();
        }

        if($action === 'download')
        {
            $pdf->download();
        }

        $app->close();

    }


}
