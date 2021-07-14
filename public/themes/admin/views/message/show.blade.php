{!! Theme::asset()->container('layimpc')->styles() !!}
<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div id="layui-layimpc-chat" class="layui-layer-content">

                <div class="layimpc-chat-box">
                    <div class="layimpc-chat layimpc-chat-friend layui-show">
                        <div class="layimpc-chat-main">
                            <ul>
                                <li class="layimpc-chat-mine">
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite><i>2021-01-29 15:49:48</i>用户</cite></div>
                                    <div class="layimpc-chat-text">您好</div>
                                </li>
                                <li>
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite>律师<i>2021-01-29 15:49:49</i></cite></div>
                                    <div class="layimpc-chat-text">您好，我现在有事不在，一会再和您联系。</div>
                                </li>
                                <li class="layimpc-chat-mine">
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite><i>2021-01-29 15:49:48</i>用户</cite></div>
                                    <div class="layimpc-chat-text">您好</div>
                                </li>
                                <li>
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite>律师<i>2021-01-29 15:49:49</i></cite></div>
                                    <div class="layimpc-chat-text">您好，我现在有事不在，一会再和您联系。</div>
                                </li>
                                <li class="layimpc-chat-mine">
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite><i>2021-01-29 15:49:48</i>用户</cite></div>
                                    <div class="layimpc-chat-text">您好</div>
                                </li>
                                <li>
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite>律师<i>2021-01-29 15:49:49</i></cite></div>
                                    <div class="layimpc-chat-text">您好，我现在有事不在，一会再和您联系。</div>
                                </li>
                                <li class="layimpc-chat-mine">
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite><i>2021-01-29 15:49:48</i>用户</cite></div>
                                    <div class="layimpc-chat-text">您好</div>
                                </li>
                                <li>
                                    <div class="layimpc-chat-user"><img src="//tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite>律师<i>2021-01-29 15:49:49</i></cite></div>
                                    <div class="layimpc-chat-text">您好，我现在有事不在，一会再和您联系。</div>
                                </li>
                            </ul>
                        </div>
                        <div class="layimpc-chat-footer">
                            <div class="layui-unselect layimpc-chat-tool" >
                                <span class="layui-icon layimpc-tool-face" title="选择表情" layimpc-event="face"></span>
                                <span class="layui-icon layimpc-tool-image" title="上传图片" layimpc-event="image"><input type="file" name="file"></span>
                                <span class="layui-icon layimpc-tool-image" title="发送文件" layimpc-event="image" data-type="file"><input type="file" name="file"></span>
                                <span class="layui-icon layimpc-tool-audio" title="发送网络音频" layimpc-event="media" data-type="audio"></span>
                                <span class="layui-icon layimpc-tool-video" title="发送网络视频" layimpc-event="media" data-type="video"></span>
                                <span class="layui-icon layimpc-tool-code" title="代码" layimpc-event="extend" lay-filter="code"></span>
                                <span class="layimpc-tool-log" layimpc-event="chatLog"><i class="layui-icon"></i>聊天记录</span>
                            </div>
                            <div class="layimpc-chat-textarea"><textarea spellcheck="false"></textarea></div>
                            <div class="layimpc-chat-bottom">
                                <div class="layimpc-chat-send">
                                    <span class="layimpc-send-close" layimpc-event="closeThisChat">关闭</span>
                                    <span class="layimpc-send-btn" layimpc-event="send">发送</span>
                                    <span class="layimpc-send-set" layimpc-event="setSend" lay-type="show"><em class="layui-edge"></em></span>
                                    <ul class="layui-anim layimpc-menu-box">
                                        <li class="layimpc-this" layimpc-event="setSend" lay-type="Enter"><i class="layui-icon"></i>按Enter键发送消息</li>
                                        <li layimpc-event="setSend" lay-type="Ctrl+Enter"><i class="layui-icon"></i>按Ctrl+Enter键发送消息</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
{!! Theme::asset()->container('ueditor')->scripts() !!}