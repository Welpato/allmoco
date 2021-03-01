<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractBaseController extends AbstractController
{
    protected const PAGE_TITLE = 'AllMoço App';

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    protected function render(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response
    {
        $parameters['menuList'] = Menu::MENU_LIST;
        $parameters['pageTitle'] = static::PAGE_TITLE;
        return parent::render(
            $view,
            $parameters,
            $response
        );
    }
}
