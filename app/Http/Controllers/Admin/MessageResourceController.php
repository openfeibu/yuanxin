<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\MessageRepository;

class MessageResourceController extends BaseController
{
    public function __construct(MessageRepository $messageRepository)
    {
        parent::__construct();
        $this->repository = $messageRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $messages = Message::join('users','users.id','=','messages.user_id')->groupBy('user_id')
                ->orderBy('is_reply','asc')
                ->orderBy('id','desc')
                ->paginate($limit,['messages.*','users.nickname','users.avatar_url']);

            return $this->response
                ->success()
                ->count($messages->total())
                ->data($messages->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('message.name'))
            ->view('message.index')
            ->output();
    }
    public function create(Request $request)
    {
        $message = $this->repository->newInstance([]);

        return $this->response->title(trans('app.new') . ' ' .trans('message.name'))
            ->view('message.create')
            ->data(compact('message'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $message = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('message.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('message/' . $message->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('message'))
                ->redirect();
        }
    }
    public function show(Request $request,Message $message)
    {
        if ($message->exists) {
            $view = 'message.show';
        } else {
            $view = 'message.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('message.name'))
            ->data(compact('message'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Message $message)
    {
        try {
            $attributes = $request->all();

            $message->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('message.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('message/' . $message->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('message/' . $message->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Message $message)
    {
        try {
            $this->repository->forceDelete([$message->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('message.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('message'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('message'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('message.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('message'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('message'))
                ->redirect();
        }
    }
}
