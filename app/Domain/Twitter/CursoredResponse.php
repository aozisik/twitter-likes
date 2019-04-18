<?php
namespace App\Domain\Twitter;

class CursoredResponse
{
    private $nextCursor = -1;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function next()
    {
        if ($this->nextCursor === 0) {
            return null;
        }

        $response = $this->request->make([
            'cursor' => $this->nextCursor
        ]);

        $this->nextCursor = $response->next_cursor;
        return $response;
    }
}
