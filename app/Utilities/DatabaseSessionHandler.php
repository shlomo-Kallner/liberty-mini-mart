<?php

namespace App\Utilities;

use App\Exceptions\JsonException;
use SessionHandlerInterface;
use App\UserSession,
    App\Utilities\Functions\Functions;

class DatabaseSessionHandler implements SessionHandlerInterface
{
    protected $userSession = null;

    /**
     * Create a new custom database driven handler instance.
     *
     * @param  \App\UserSession  $us
     * @return void
     */
    public function __construct(UserSession $us)
    {
        $this->userSession = Functions::getVar($us, null);
    }

    public function hasUserSession()
    {
        return Functions::testVar($this->userSession);
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        // throw new JsonException(request(), __METHOD__, $this->userSession);
        return $this->hasUserSession()
            ? $this->userSession->getPayload(false)
            : '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        if ($this->hasUserSession()) {
            /* updateSession(
                    string $session_id, $user, string $ip, string $userAgent,
                    $payload = null, int $lastActivity = 2
                )
            */
            $si = $this->userSession->session_id ?: $sessionId;
            $user = $this->userSession->user_id;
            $ip = $this->userSession->ip_address;
            $userAgent = $this->userSession->user_agent;

            $this->userSession->updateSession(
                $si, $user, $ip, $userAgent, $data
            );
        }

        return true;
    }

    /// Overides for overiding's sake..

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        // NO-OP!
    }
}