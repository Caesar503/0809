<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>购物车列表</title>
    <style>
        html,body {
            width: 100%;
            height: 70%;
            margin: 0;
            padding: 0;
            /*overflow: hidden;*/
        }
        .container{
            width: 80％;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #000000;
        }
    </style>
</head>
<body>
<div id="jsi-particle-container" class="container"></div>
<h3>购物车列表</h3>
<hr>
<table border="1">
    <tr>
        <td align="center">ID</td>
        <td align="center">商品名称</td>
        <td align="center">本店售价</td>
        <td align="center">购买数量</td>
        <td align="center">总价</td>
        <td align="center">商家id</td>
        <td align="center">操作</td>
    </tr>
    @foreach($res as $v)
        <tr>
            <td class="gid">{{$v['id']}}</td>
            <td>{{$v['goods_name']}}</td>
            <td>{{$v['goods_price']}}</td>
            <td><button id="addNum" b_num="{{$v['goods_num']}}">+</button><text gid="{{$v['id']}}">{{$v['num']}}</text><button id="jianNum" b_num="{{$v['goods_num']}}">-</button></td>
            <td class="allPrice">{{$v['num']*$v['goods_price']}}</td>
            <td>{{$v['store_id']}}</td>
            <td><button id="addCar" b_id="{{$v['id']}}">删除该商品</button></td>
        </tr>
    @endforeach
</table>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="getDing">点击生成订单</button>
</body>
</html>
<script src="/js/jquery-3.2.1.min.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    $(function(){
        //从购物车删除该商品
        $(document).on('click','#addCar',function(){
            //获取商品的id
            var b_id = $(this).attr('b_id');
            var uid = "{{$uid}}";
            $.ajax({
                url:'/cart/del',
                type:'post',
                data:{id:b_id,uid:uid},
                success:function(res) {
                    if(res ==1 )
                    {
                        alert('删除该商品成功！');
                        history.go(0);
                    }else{
                        alert('删除该商品失败！');
                    }
                }
            })
        })
        //添加数量
        $(document).on('click','#addNum',function(){
            //获取剩余库存量
            var num = $(this).attr('b_num');
            //获取商品的id
            var gid = $(this).next().attr('gid');
            //获取购买数量
            var m_num = $(this).next().text();
            //获取商品的单价
            var onePrice = $(this).parent().prev('td').text();

            var _this = $(this);
            //将要购买的数量
            var j_num = Number(m_num)+1;
            if(j_num>num)
            {
                alert('该商品没有那么多库存，谢谢！');
            }else{
                $.ajax({
                    url:'/cart/num/add',
                    type:'post',
                    data:{id:gid,num:j_num},
                    success:function(res) {
                        if(res ==1 )
                        {
                            alert('商品数量添加成功！');
                            _this.next().text(j_num);
                            _this.parent().next('td').text(j_num*onePrice);
                        }else{
                            alert('商品数量添加失败！');
                        }
                    }
                })
            }

        });
        //减少数量
        $(document).on('click','#jianNum',function(){
            //获取剩余库存量
            var num = $(this).attr('b_num');
            //获取商品的id
            var gid = $(this).prev().attr('gid');
            //获取购买数量
            var m_num = $(this).prev().text();
            //获取商品的单价
            var onePrice = $(this).parent().prev('td').text();

            var _this = $(this);
            //将要购买的数量
            var j_num = Number(m_num)-1;
            if(j_num<=0)
            {
                alert('该商品在您的购物车中最后一件，┭┮﹏┭┮！');
            }else{
                $.ajax({
                    url:'/cart/num/jian',
                    type:'post',
                    data:{id:gid,num:j_num},
                    success:function(res) {
                        if(res ==1 )
                        {
                            alert('商品数量减少成功！');
                            _this.prev().text(j_num);
                            _this.parent().next('td').text(j_num*onePrice);
                        }else{
                            alert('商品数量减少失败！');
                        }
                    }
                })
            }

        });
        //点击生成订单
        $(document).on('click','#getDing',function(){
            //计算总价
            var box = $('.allPrice');
            var price = 0;
            box.each(function (index) {
                price += parseInt($(this).text());
            })
            alert("该订单总价为："+price);


            //商品id
            var gid = $('.gid');
            var g_id ='';
            gid.each(function (index) {
                g_id +=$(this).text()+',';
            })

            //生成订单
            $.ajax({
                url:'/add/order',
                type:'post',
                data:{allPrice:price,gid:g_id},
                success:function(res) {
                    if(res == 1)
                    {
                        alert('加入订单表成功！');
                        history.go(0);
                    }else{
                        alert('加入购物车失败！');
                    }
                }
            })


        });







        //动画特效
        var RENDERER = {
            PARTICLE_COUNT : 1000,
            PARTICLE_RADIUS : 1,
            MAX_ROTATION_ANGLE : Math.PI / 60,
            TRANSLATION_COUNT : 500,

            init : function(strategy){
                this.setParameters(strategy);
                this.createParticles();
                this.setupFigure();
                this.reconstructMethod();
                this.bindEvent();
                this.drawFigure();
            },
            setParameters : function(strategy){
                this.$window = $(window);

                this.$container = $('#jsi-particle-container');
                this.width = this.$container.width();
                this.height = this.$container.height();

                this.$canvas = $('<canvas />').attr({width : this.width, height : this.height}).appendTo(this.$container);
                this.context = this.$canvas.get(0).getContext('2d');

                this.center = {x : this.width / 2, y : this.height / 2};

                this.rotationX = this.MAX_ROTATION_ANGLE;
                this.rotationY = this.MAX_ROTATION_ANGLE;
                this.strategyIndex = 0;
                this.translationCount = 0;
                this.theta = 0;

                this.strategies = strategy.getStrategies();
                this.particles = [];
            },
            createParticles : function(){
                for(var i = 0; i < this.PARTICLE_COUNT; i ++){
                    this.particles.push(new PARTICLE(this.center));
                }
            },
            reconstructMethod : function(){
                this.setupFigure = this.setupFigure.bind(this);
                this.drawFigure = this.drawFigure.bind(this);
                this.changeAngle = this.changeAngle.bind(this);
            },
            bindEvent : function(){
                this.$container.on('click', this.setupFigure);
                this.$container.on('mousemove', this.changeAngle);
            },
            changeAngle : function(event){
                var offset = this.$container.offset(),
                    x = event.clientX - offset.left + this.$window.scrollLeft(),
                    y = event.clientY - offset.top + this.$window.scrollTop();

                this.rotationX = (this.center.y - y) / this.center.y * this.MAX_ROTATION_ANGLE;
                this.rotationY = (this.center.x - x) / this.center.x * this.MAX_ROTATION_ANGLE;
            },
            setupFigure : function(){
                for(var i = 0, length = this.particles.length; i < length; i++){
                    this.particles[i].setAxis(this.strategies[this.strategyIndex]());
                }
                if(++this.strategyIndex == this.strategies.length){
                    this.strategyIndex = 0;
                }
                this.translationCount = 0;
            },
            drawFigure : function(){
                requestAnimationFrame(this.drawFigure);

                this.context.fillStyle = 'rgba(0, 0, 0, 0.2)';
                this.context.fillRect(0, 0, this.width, this.height);

                for(var i = 0, length = this.particles.length; i < length; i++){
                    var axis = this.particles[i].getAxis2D(this.theta);

                    this.context.beginPath();
                    this.context.fillStyle = axis.color;
                    this.context.arc(axis.x, axis.y, this.PARTICLE_RADIUS, 0, Math.PI * 2, false);
                    this.context.fill();
                }
                this.theta++;
                this.theta %= 360;

                for(var i = 0, length = this.particles.length; i < length; i++){
                    this.particles[i].rotateX(this.rotationX);
                    this.particles[i].rotateY(this.rotationY);
                }
                this.translationCount++;
                this.translationCount %= this.TRANSLATION_COUNT;

                if(this.translationCount == 0){
                    this.setupFigure();
                }
            }
        };
        var STRATEGY = {
            SCATTER_RADIUS :150,
            CONE_ASPECT_RATIO : 1.5,
            RING_COUNT : 5,

            getStrategies : function(){
                var strategies = [];

                for(var i in this){
                    if(this[i] == arguments.callee || typeof this[i] != 'function'){
                        continue;
                    }
                    strategies.push(this[i].bind(this));
                }
                return strategies;
            },
            createSphere : function(){
                var cosTheta = Math.random() * 2 - 1,
                    sinTheta = Math.sqrt(1 - cosTheta * cosTheta),
                    phi = Math.random() * 2 * Math.PI;

                return {
                    x : this.SCATTER_RADIUS * sinTheta * Math.cos(phi),
                    y : this.SCATTER_RADIUS * sinTheta * Math.sin(phi),
                    z : this.SCATTER_RADIUS * cosTheta,
                    hue : Math.round(phi / Math.PI * 30)
                };
            },
            createTorus : function(){
                var theta = Math.random() * Math.PI * 2,
                    x = this.SCATTER_RADIUS + this.SCATTER_RADIUS / 6 * Math.cos(theta),
                    y = this.SCATTER_RADIUS / 6 * Math.sin(theta),
                    phi = Math.random() * Math.PI * 2;

                return {
                    x : x * Math.cos(phi),
                    y : y,
                    z : x * Math.sin(phi),
                    hue : Math.round(phi / Math.PI * 30)
                };
            },
            createCone : function(){
                var status = Math.random() > 1 / 3,
                    x,
                    y,
                    phi = Math.random() * Math.PI * 2,
                    rate = Math.tan(30 / 180 * Math.PI) / this.CONE_ASPECT_RATIO;

                if(status){
                    y = this.SCATTER_RADIUS * (1 - Math.random() * 2);
                    x = (this.SCATTER_RADIUS - y) * rate;
                }else{
                    y = -this.SCATTER_RADIUS;
                    x = this.SCATTER_RADIUS * 2 * rate * Math.random();
                }
                return {
                    x : x * Math.cos(phi),
                    y : y,
                    z : x * Math.sin(phi),
                    hue : Math.round(phi / Math.PI * 30)
                };
            },
            createVase : function(){
                var theta = Math.random() * Math.PI,
                    x = Math.abs(this.SCATTER_RADIUS * Math.cos(theta) / 2) + this.SCATTER_RADIUS / 8,
                    y = this.SCATTER_RADIUS * Math.cos(theta) * 1.2,
                    phi = Math.random() * Math.PI * 2;

                return {
                    x : x * Math.cos(phi),
                    y : y,
                    z : x * Math.sin(phi),
                    hue : Math.round(phi / Math.PI * 30)
                };
            }
        };
        var PARTICLE = function(center){
            this.center = center;
            this.init();
        };
        PARTICLE.prototype = {
            SPRING : 0.01,
            FRICTION : 0.9,
            FOCUS_POSITION : 300,
            COLOR : 'hsl(%hue, 100%, 70%)',

            init : function(){
                this.x = 0;
                this.y = 0;
                this.z = 0;
                this.vx = 0;
                this.vy = 0;
                this.vz = 0;
                this.color;
            },
            setAxis : function(axis){
                this.translating = true;
                this.nextX = axis.x;
                this.nextY = axis.y;
                this.nextZ = axis.z;
                this.hue = axis.hue;
            },
            rotateX : function(angle){
                var sin = Math.sin(angle),
                    cos = Math.cos(angle),
                    nextY = this.nextY * cos - this.nextZ * sin,
                    nextZ = this.nextZ * cos + this.nextY * sin,
                    y = this.y * cos - this.z * sin,
                    z = this.z * cos + this.y * sin;

                this.nextY = nextY;
                this.nextZ = nextZ;
                this.y = y;
                this.z = z;
            },
            rotateY : function(angle){
                var sin = Math.sin(angle),
                    cos = Math.cos(angle),
                    nextX = this.nextX * cos - this.nextZ * sin,
                    nextZ = this.nextZ * cos + this.nextX * sin,
                    x = this.x * cos - this.z * sin,
                    z = this.z * cos + this.x * sin;

                this.nextX = nextX;
                this.nextZ = nextZ;
                this.x = x;
                this.z = z;
            },
            rotateZ : function(angle){
                var sin = Math.sin(angle),
                    cos = Math.cos(angle),
                    nextX = this.nextX * cos - this.nextY * sin,
                    nextY = this.nextY * cos + this.nextX * sin,
                    x = this.x * cos - this.y * sin,
                    y = this.y * cos + this.x * sin;

                this.nextX = nextX;
                this.nextY = nextY;
                this.x = x;
                this.y = y;
            },
            getAxis3D : function(){
                this.vx += (this.nextX - this.x) * this.SPRING;
                this.vy += (this.nextY - this.y) * this.SPRING;
                this.vz += (this.nextZ - this.z) * this.SPRING;

                this.vx *= this.FRICTION;
                this.vy *= this.FRICTION;
                this.vz *= this.FRICTION;

                this.x += this.vx;
                this.y += this.vy;
                this.z += this.vz;

                return {x : this.x, y : this.y, z : this.z};
            },
            getAxis2D : function(theta){
                var axis = this.getAxis3D(),
                    scale = this.FOCUS_POSITION / (this.FOCUS_POSITION + axis.z);

                return {x : this.center.x + axis.x * scale, y : this.center.y - axis.y * scale, color : this.COLOR.replace('%hue', this.hue + theta)};
            }
        };
        $(function(){
            RENDERER.init(STRATEGY);
        });
    })
</script>
