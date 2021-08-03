<div class="main">
    <div class="main_full fb-clearfix" style="margin-top: 15px;">
        <div class="layui-col-md12 layui-card-box-home-block">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>今日预约</b>
                            <label></label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_appointment_count }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>总预约数</b>
                            <label></label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $appointment_count }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>今日用户</b>
                            <label></label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_user_count }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>总用户</b>
                            <label></label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $user_count }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card-box fb-clearfix layui-col-space15">

                <div class="layui-col-sm6 layui-col-md6">
                    <div class="power-box fb-clearfix">
                        <p>常用功能</p>
                        <div class="power-box-con">
                            @if(Auth::user()->isSuperuser() || Auth::user()->hasPermission('appointment.index'))
                                <div class="power-box-item layui-col-md6">
                                    <a href="{{ guard_url('appointment') }}">
                                        {{ trans('appointment.name') }}
                                    </a>
                                </div>
                            @endif
                            @if(Auth::user()->isSuperuser() || Auth::user()->hasPermission('report.index'))
                                <div class="power-box-item layui-col-md6">
                                    <a href="{{ guard_url('report') }}">
                                        {{ trans('report.name') }}
                                    </a>
                                </div>
                            @endif
                            @if(Auth::user()->isSuperuser() || Auth::user()->hasPermission('project.index'))
                                <div class="power-box-item layui-col-md6">
                                    <a href="{{ guard_url('project') }}">
                                        {{ trans('project.name') }}
                                    </a>
                                </div>
                            @endif

                            <div class="power-box-item layui-col-md6">
                                <a href="{{ setting('customer_service') }}" target="_blank">
                                   客服中心
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>