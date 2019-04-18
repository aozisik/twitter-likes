<?php
namespace App\Domain\Twitter;

class CursoredResponse
{
    private $nextCursor;
    private $maxRequestsPerMinute;

    public function __construct(Request $request, $nextCursor = -1, $maxRequestsPerMinute = 15)
    {
        $this->request = $request;
        $this->nextCursor = $nextCursor;
        $this->maxRequestsPerMinute = $maxRequestsPerMinute;
    }

    public function hasMore()
    {
        return $this->nextCursor !== 0;
    }

    private function throttle()
    {
        if (!$this->maxRequestsPerMinute) {
            return;
        }
        $waitSeconds = 60 / $this->maxRequestsPerMinute;
        sleep($waitSeconds + 1);
    }

    public function next()
    {
        if (!$this->hasMore()) {
            return null;
        }

        $this->throttle();

        $response = $this->request->make([
            'cursor' => $this->nextCursor
        ]);

        $this->nextCursor = $response->next_cursor;
        return $response;
    }
}
