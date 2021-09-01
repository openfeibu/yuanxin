<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Appointment;
use App\Repositories\Eloquent\AppointmentRepository;
use App\Repositories\Eloquent\AppointmentRepositoryInterface;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Resource controller class for page.
 */
class AppointmentResourceController extends BaseController
{
    /**
     * Initialize page resource controller.
     *
     * @param type AppointmentRepository $appointmentRepository
     *
     */
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        parent::__construct();
        $this->repository = $appointmentRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request){
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->setPresenter(\App\Repositories\Presenter\AppointmentListPresenter::class)
                ->orderBy('id','desc')
                ->getDataTable($limit);
            return $this->response
                ->success()
                ->count($data['recordsTotal'])
                ->data($data['data'])
                ->output();
        }
        return $this->response->title(trans('appointment.name'))
            ->view('appointment.index')
            ->output();
    }
    public function create(Request $request)
    {
        $appointment = $this->repository->newInstance([]);

        return $this->response->title(trans('appointment.name'))
            ->view('appointment.create')
            ->data(compact('appointment'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $appointment = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('appointment.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('appointment/' ))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('appointment/'))
                ->redirect();
        }
    }
    public function show(Request $request,Appointment $appointment)
    {
        if ($appointment->exists) {
            $view = 'appointment.show';
        } else {
            $view = 'appointment.new';
        }

        return $this->response->title(trans('appointment.name'))
            ->data(compact('appointment'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Appointment $appointment)
    {
        try {
            $attributes = $request->all();

            $appointment->update($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('appointment.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('appointment/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('appointment/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,Appointment $appointment)
    {
        try {
            $appointment->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('appointment.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('appointment'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('appointment'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('appointment.name')]))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('appointment'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('appointment'))
                ->redirect();
        }
    }
    public function check(Request $request)
    {
        try {
            $id = $request->get('id');
            $code = strtoupper($request->get('code',''));

            $appointment = $this->repository->find($id);
            if(strtoupper($appointment->code) == $code)
            {
                Appointment::where('id',$id)->update(['status' => 'check']);
            }else{
                throw new OutputServerMessageException('验证码不正确');
            }

            return $this->response->message("核销成功！")
                ->status("success")
                ->http_code(202)
                ->url(guard_url('appointment'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('appointment'))
                ->redirect();
        }
    }
    public function searchCode(Request $request)
    {
        try {
            $code = strtoupper($request->get('code',''));

            $appointment = $this->repository->where('code',$code)->where('status','unchecked')->first(['id']);
            if(!$appointment)
            {
                throw new OutputServerMessageException('未发现符合的未核销预约单，请检查验证码！');
            }

            return $this->response->message("搜索成功！")
                ->status("success")
                ->http_code(202)
                ->url(guard_url('appointment/'.$appointment->id."?code=".$code))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('appointment'))
                ->redirect();
        }
    }
}