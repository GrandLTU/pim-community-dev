<?php

declare(strict_types=1);

namespace Akeneo\Connectivity\Connection\Infrastructure\Apps\Controller;

use Akeneo\Connectivity\Connection\Application\Apps\Command\AuthorizeAppCommand;
use Akeneo\Connectivity\Connection\Application\Apps\Command\AuthorizeAppHandler;
use Akeneo\Connectivity\Connection\Domain\Apps\Exception\AuthorizeAppInvalidRequest;
use Akeneo\Platform\Bundle\FeatureFlagBundle\FeatureFlag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * @copyright 2021 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AuthorizeAction
{
    private AuthorizeAppHandler $handler;
    private RouterInterface $router;
    private FeatureFlag $featureFlag;

    public function __construct(
        AuthorizeAppHandler $handler,
        RouterInterface $router,
        FeatureFlag $featureFlag
    ) {
        $this->handler = $handler;
        $this->router = $router;
        $this->featureFlag = $featureFlag;
    }

    public function __invoke(Request $request): Response
    {
        if (!$this->featureFlag->isEnabled()) {
            throw new NotFoundHttpException();
        }

        $command = new AuthorizeAppCommand(
            $request->query->get('client_id', ''),
            $request->query->get('response_type', ''),
            $request->query->get('scope', ''),
            $request->query->get('redirect_uri', ''),
        );

        try {
            $this->handler->handle($command);
        } catch (AuthorizeAppInvalidRequest $e) {
            return new RedirectResponse('/#' . $this->router->generate('akeneo_connectivity_connection_connect_apps_authorize', [
                'error' => $e->getConstraintViolationList()[0]->getMessage(),
            ]));
        }

        return new Response('Not implemented yet');
    }
}