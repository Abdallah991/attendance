<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;





class ApplicantController extends Controller
{
    use HttpResponses;

    // * write comments on applicant
    public function CommentOnApplicant(StoreCommentRequest $request)
    {
        // get request parameters
        $commentedBy = $request->commentedBy;
        $platformId = $request->platformId;
        $commentDone = $request->comment;
        // if comment exist
        $commentExsist = false;
        // get all comments on user
        $comments = Comment::where('platformId', $platformId)->get();
        // check if comment exsist for user
        foreach ($comments as $comment) {
            // filter for staff member 
            if ($comment->commentedBy == $commentedBy) {
                $commentExsist = true;
                break;
            }
        }

        // if exist update it
        if ($commentExsist) {
            $commentToBeUpdated = Comment::where(
                [
                    'platformId' => $platformId,
                    'commentedBy' => $commentedBy

                ]
            )->first();
            $commentToBeUpdated->comment = $commentDone;

            $commentToBeUpdated->save();


            return $this->success([
                'comment' => $commentToBeUpdated,
                'message' => "comment was done successfully"
            ]);
        } else {
            // if not create it
            $comment = new CommentResource(Comment::create([
                'platformId' => $platformId,
                'comment' => $commentDone,
                'commentedBy' => $commentedBy
            ]));
            return $this->success([
                'comment' => $comment,
                'message' => "comment was done successfully"
            ]);
        }
    }


    // get all comments with platformId
    public function getComments(Request $request)
    {
        $platformId = $request->platformId;
        $comments = Comment::where('platformId', $platformId)->get();
        return $this->success([
            'comments' => $comments,
            'message' => "comment was done successfully"
        ]);
    }
}
