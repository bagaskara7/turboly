<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Task Manager</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/animate.min.css" rel="stylesheet"/>
    <link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker3.min.css">
    <style>
        textarea {
            resize: vertical;
        }
    </style>
    <link href="css/demo.css" rel="stylesheet" />
</head>
<body>
    <div id="wrapper" class="wrapper" v-cloak>
        <div class="sidebar" data-color="azure" data-image="img/sidebar-4.jpg">
        	<div class="sidebar-wrapper">
                <div class="logo">
                    <a href="http://www.bagaskara.id" class="simple-text">
                        Task Manager
                    </a>
                </div>
                <ul class="nav">
                    <li class="active">
                        <a href="">
                            <i class="pe-7s-graph"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="pe-7s-note"></i>
                            <p>Tasks</p>
                        </a>
                    </li>
                </ul>
        	</div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Dashboard</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-globe"></i>
                                        <b class="caret"></b>
                                        <span class="notification">5</span>
                                  </a>
                                  <ul class="dropdown-menu">
                                    <li><a href="#">Notification 1</a></li>
                                    <li><a href="#">Notification 2</a></li>
                                    <li><a href="#">Notification 3</a></li>
                                    <li><a href="#">Notification 4</a></li>
                                    <li><a href="#">Another notification</a></li>
                                  </ul>
                            </li>
                            <li>
                                <a href="#">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card ">
                                <div class="header">
                                    <h4 class="title">Today's Tasks</h4>
                                    <p class="category">Today's uncompleted tasks</p>
                                </div>
                                <div class="content">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon" style="border-radius: 0;padding: 0;">
                                                <a href="#" id="descriptionToggle" title="Toggle Description Field" v-on:click="toggleDescriptionForm" style="padding: 12px;"><i class="pe-7s-news-paper"></i></a>
                                            </div>
                                            <input v-model="taskName" v-on:keyup.enter="addTask" class="form-control" placeholder="Enter task name (hit enter to add) ..." style="border-radius: 0" />
                                            <div class="input-group-addon" style="padding: 0;border-left: none;">
                                                <select v-model="taskPriority" v-on:keyup.enter="addTask" class="form-control" style="width: auto;border: medium none; height: 38px;">
                                                    <option value="">- Priority -</option>
                                                    <option value="1">Urgent</option>
                                                    <option value="2">Normal</option>
                                                    <option value="3">Low</option>
                                                </select>
                                            </div>
                                            <div class="input-group-addon" style="border-radius: 0;padding: 0;">
                                                <input v-model="taskDate" v-on:keyup.enter="addTask" class="form-control" style="width: 150px; border: medium none; padding: 12px; height: 38px;" readonly="" />
                                            </div>
                                        </div>
                                        <textarea v-model="taskDescription" class="description form-control" placeholder="Enter task description (hit CTRL/CMD + Enter to add)" style="display:none;min-height:75px;border-top-left-radius: 0;border-top-right-radius: 0;border-top:none;" v-on:keydown.enter="handleDescription($event)"></textarea>
                                    </div>
                                    <form id="search">
                                        Search <input name="query" v-model="searchQuery">
                                    </form>
                                    <div class="table-full-width">
                                        <task-grid
                                            :data="tasks"
                                            :columns="columns"
                                            :filter-key="searchQuery"></task-grid>
                                    </div>
                                    <div class="footer">
                                        <hr>
                                        <div class="stats">
                                            <i class="fa fa-history"></i> Updated 3 minutes ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Users Behavior</h4>
                                    <p class="category">24 Hours performance</p>
                                </div>
                                <div class="content">
                                    <div id="chartHours" class="ct-chart"></div>
                                    <div class="footer">
                                        <div class="legend">
                                            <i class="fa fa-circle text-info"></i> Open
                                            <i class="fa fa-circle text-danger"></i> Click
                                            <i class="fa fa-circle text-warning"></i> Click Second Time
                                        </div>
                                        <hr>
                                        <div class="stats">
                                            <i class="fa fa-history"></i> Updated 3 minutes ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright">
                        &copy; 2016 <a href="http://www.bagaskara.id">Bagaskara Wisnu Gunawan</a>, all rights reserved. Template by <a href="http://www.creative-tim.com">Creative Tim</a>.
                    </p>
                </div>
            </footer>
        </div>
    </div>
    <script type="text/x-template" id="task-table">
        <table class="table">
            <thead>
                <tr>
                    <th v-for="head in columns"
                        @click="(head != '#' & head != '' & head != 'due date') ? sortBy(head) : ''"
                        :class="{ active: sortKey == head }">
                            @{{ head | capitalize }}
                            <span v-if="head != '#' & head != '' & head != 'due date'" class="fa"
                                :class="sortOrders[head] > 0 ? 'fa-sort-asc' : 'fa-sort-desc'"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in data | filterBy filterKey | orderBy sortKey sortOrders[sortKey]">
                    <td style="vertical-align: text-top;width: 15px">
                        <label title="Mark as completed">
                            <input type="checkbox" value="" v-on:click="markTaskAsComplete($index, task)">
                        </label>
                    </td>
                    <td style="vertical-align: text-top;width: 50%">
                        <b>@{{ task.name }}</b>
                        <br />
                        <span id="task-description-@{{ task.id }}" style="display: none;color: grey;word-break: break-all;">@{{ task.description }}</span>
                    </td>
                    <td style="vertical-align: text-top;">
                        <div v-if="task.priority == 1" style="color: red;">
                            Urgent
                        </div>
                        <div v-if="task.priority == 2" style="color: blue;">
                            Normal
                        </div>
                        <div v-if="task.priority == 3" style="color: green;">
                            Low
                        </div>
                    </td>
                    <td style="vertical-align: text-top;">
                        @{{ task.date }}
                    </td>
                    <td class="text-right" style="vertical-align: text-top;">
                        <button type="button" rel="tooltip" title="Toggle Description" class="btn btn-info btn-simple btn-xs" v-on:click="toggleDescription(task.id)">
                            <i class="fa fa-file-text"></i>
                        </button>
                        <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs" v-on:click="editTask($index, task)">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs" v-on:click="removeTask($index, task)">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="data.length < 1">
                    <td colspan="5" align="center"><b>Hoorayy!</b> There are no tasks due today.</td>
                </tr>
            </tbody>
        </table>
    </script>
    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="js/chartist.min.js"></script>
    <script src="js/bootstrap-notify.js"></script>
    <script src="js/light-bootstrap-dashboard.js"></script>
    <script src="js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            // demo.initChartist();

            $.notify({
                icon: 'pe-7s-note2',
                message: "Welcome to <b>Task Manager</b> - manage your task to save your life."
            },{
                placement: {
                    from: 'bottom'
                },
                type: 'info',
                timer: 2500
            });
        });

        Vue.component('task-grid', {
            template: '#task-table',
            props: {
                data: Array,
                columns: Array,
                filterKey: String
            },
            data: function () {
                var sortOrders = {};

                this.columns.forEach(function (key) {
                    if (key != '#' && key != '' && key != 'due date') {
                        sortOrders[key] = 1;
                    }
                });

                return {
                    sortKey: '',
                    sortOrders: sortOrders
                }
            },
            methods: {
                sortBy: function (key) {
                    this.sortKey = key;
                    this.sortOrders[key] = this.sortOrders[key] * -1;
                },
                editTask: function(index, task) {
                    console.log('editing task ' + task.id + ' at index #' + index);
                },
                removeTask: function (index, task) {
                    main.tasks.splice(index, 1);

                    $.notify({
                        icon: 'pe-7s-close-circle',
                        message: "<b>Deleted!</b> You have deleted \"" + task.name + "\" from your tasks list."
                    },{
                        placement: {
                            from: 'bottom'
                        },
                        type: 'danger',
                        timer: 2000
                    });
                },
                markTaskAsComplete: function(index, task) {
                    main.tasks.splice(index, 1);

                    $.notify({
                        icon: 'pe-7s-check',
                        message: "<b>Congratulations!</b> You have marked \"" + task.name + "\" as completed!"
                    },{
                        placement: {
                            from: 'bottom'
                        },
                        type: 'success',
                        timer: 2000
                    });
                },
                toggleDescription: function (id) {
                    var dom = $('#task-description-' + id);

                    if (dom.is(':visible')) {
                        dom.hide();
                    } else {
                        dom.show();
                    }
                }
            }
        });

        var id = 1;
        var main = new Vue({
            el: '#wrapper',
            data: {
                taskName: '',
                taskDate: '{{ date("d M Y") }}',
                taskDescription: '',
                taskPriority: 0,
                searchQuery: '',
                columns: [
                    '#',
                    'name',
                    'priority',
                    'due date',
                    ''
                ],
                tasks: [
                    {
                        id: 1,
                        name: 'Add some tasks',
                        priority: 1,
                        date: '16 Jun 2016',
                        description: 'Some tasks are awesome'
                    }
                ]
            },
            methods: {
                addTask: function () {
                    var name = this.taskName.trim();
                    var date = this.taskDate.trim();
                    var priority = this.taskPriority;
                    var description = this.taskDescription.trim();
                    id++;

                    if (name && date && priority) {
                        this.tasks.unshift({
                            id: id,
                            name: name,
                            date: date,
                            priority: priority,
                            description: description
                        });

                        this.taskName = '';
                        this.taskDate = '{{ date("d M Y") }}';
                        this.taskPriority = 0;
                        this.taskDescription = '';
                        
                        $('[rel=tooltip]').tooltip();
                    }
                },
                handleDescription: function(e) {
                    if (e.metaKey || e.ctrlKey) {
                        this.addTask();
                    }
                },
                toggleDescriptionForm: function() {
                    $('.description').toggle();
                }
            }
        });
    </script>
</body>
</html>