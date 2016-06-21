@extends('base')

@section('custom-css')
	<style>
		.datepicker .datepicker-input {
			border: none;
			height: 35px;
		}

		.datepicker-dateRange {
			white-space: normal;
		}
	</style>
@stop

@section('custom-js')
    <script type="text/x-template" id="task-table">
        <table class="table">
            <thead>
                <tr>
                    <th @click="sortBy('completed')"
                        :class="{ active: sortKey == 'completed' }">
                        #
                        <span class="fa"
                            :class="sortOrders['completed'] > 0 ? 'fa-sort-asc' : 'fa-sort-desc'"></span>
                    </th>
                    <th @click="sortBy('name')"
                        :class="{ active: sortKey == 'name' }">
                        NAME
                        <span class="fa"
                            :class="sortOrders['name'] > 0 ? 'fa-sort-asc' : 'fa-sort-desc'"></span>
                    </th>
                    <th @click="sortBy('priority')"
                        :class="{ active: sortKey == 'priority' }">
                        PRIORITY
                        <span class="fa"
                            :class="sortOrders['priority'] > 0 ? 'fa-sort-asc' : 'fa-sort-desc'"></span>
                    </th>
                    <th @click="sortBy('date')"
                        :class="{ active: sortKey == 'date' }">
                        DUE DATE
                        <span class="fa"
                            :class="sortOrders['date'] > 0 ? 'fa-sort-asc' : 'fa-sort-desc'"></span>
                    </th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in data | filterBy filterKey in 'name' 'description' | orderBy sortKey sortOrders[sortKey] | filterTasks filterTaskKey" track-by="$index">
                    <td style="vertical-align: text-top;width: 58px">
						<tooltip
							effect="scale"
							placement="top"
							content="Mark as completed"
							v-if="!task.completed">
                            <input type="checkbox" value="" v-on:click="markTaskAsComplete($index, task)" :checked="task.completed">
                        </tooltip>
                        <tooltip
							effect="scale"
							placement="top"
							content="Mark as uncompleted"
							v-if="task.completed">
                            <input type="checkbox" value="" v-on:click="markTaskAsUncomplete($index, task)" :checked="task.completed">
                        </tooltip>
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
                        <button type="button" rel="tooltip" title="Remove Task" class="btn btn-danger btn-simple btn-xs" v-on:click="removeTask($index, task)">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="!data.length">
                    <td colspan="5" align="center"><b>Hoorayy!</b> There are no tasks available. Add a new one, now.</td>
                </tr>
            </tbody>
        </table>
        <modal title="Edit Task" :show.sync="editModal" effect="fade" width="800" :callback="saveTask">
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="border-radius: 0;padding: 0;">
                            <a href="#" id="descriptionToggle" title="Toggle Description Field" v-on:click="toggleDescriptionForm" style="padding: 12px;"><i class="pe-7s-news-paper"></i></a>
                        </div>
                        <input v-model="taskName" v-on:keyup.enter="saveTask" class="form-control" placeholder="Enter task name (hit enter to add) ..." style="border-radius: 0" id="taskName" />
                        <div class="input-group-addon" style="padding: 0;" id="taskPriorityHolder">
                            <select v-model="taskPriority" v-on:keyup.enter="saveTask" class="form-control" style="width: auto;border: medium none; height: 38px;" id="taskPriority">
                                <option value="">- Priority -</option>
                                <option value="1" style="color: red;">Urgent</option>
                                <option value="2" style="color: blue;">Normal</option>
                                <option value="3" style="color: green;">Low</option>
                            </select>
                        </div>
                        <div class="input-group-addon" style="border-radius: 0;padding: 0;">
                            <datepicker
                                :value.sync="taskDate"
                                :disabled-days-of-Week="disabled"
                                :format="format"
                                :show-reset-button="reset"
                                v-model="taskDate"
                                v-on:keyup.enter="saveTask">
                            </datepicker>
                        </div>
                    </div>
                    <textarea v-model="taskDescription" class="description form-control" placeholder="Enter task description (hit CTRL/CMD + Enter to add)" style="display:none;min-height:75px;border-top-left-radius: 0;border-top-right-radius: 0;border-top:none;" v-on:keydown.enter="handleDescription($event)"></textarea>
                </div>
            </div>
        </modal>
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
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

        var grid = Vue.component('task-grid', {
            template: '#task-table',
            components: {
            	tooltip: VueStrap.tooltip,
                modal: VueStrap.modal,
                datepicker: VueStrap.datepicker
            },
            filters: {
            	filterTasks: function(value, index) {
            		if (index == 2) {
            			return value;
            		}

					var matches = [];

					value.forEach(function(obj, idx) {
						if (obj.completed == index) {
							matches.push(obj);
						}
					});

					return matches;
            	}
            },
            props: {
                data: Array,
                columns: Array,
                filterTaskKey: String,
                filterKey: String
            },
            data: function () {
                var sortOrders = {};

                this.columns.forEach(function (key) {
                    if (key != '') {
                    	if (key == '#') {
                    		key = 'completed';
                    	} else if (key == 'due date') {
                    		key = 'date';
                    	}

                        sortOrders[key] = 1;
                    }
                });

                return {
                    editModal: false,
                    taskId: 0,
                    taskName: '',
                    taskDate: '{{ date('Y-m-d') }}',
                    taskPriority: '',
                    taskDescription: '',
                    format: 'yyyy-MM-dd',
                    sortKey: 'date',
                    sortOrders: sortOrders
                }
            },
            methods: {
		        arrayObjectIndexOf: function (arr, searchTerm) {
		            return arr.map(function(e) { return e.id; }).indexOf(Number(searchTerm));
		        },
                sortBy: function (key) {
                	if (key == '#') {
                		key = 'completed';
                	}

                    this.sortKey = key;
                    this.sortOrders[key] = this.sortOrders[key] * -1;
                },
                editTask: function(index, task) {
                    this.editModal = true;

                    this.taskId = task.id;
                    this.taskName = task.name;
                    this.taskDate = task.date;
                    this.taskPriority = task.priority;
                    this.taskDescription = task.description;

                    this.toggleDescriptionForm();
                },
                saveTask: function() {
                    var id = this.taskId;
                    var name = this.taskName.trim();
                    var date = this.taskDate.trim();
                    var priority = this.taskPriority;
                    var description = this.taskDescription.trim();

                    if (!name) {
                        $('.modal-body #taskName').css({ borderColor: 'red' });
                        $('.modal-body #taskName').focus();
                        return;
                    } else {
                        $('.modal-body #taskName').css({ borderColor: '' });
                    }

                    if (!priority) {
                        $('.modal-body #taskPriorityHolder').css({ borderColor: 'red' });
                        $('.modal-body #taskPriority').focus();
                        return;
                    } else {
                        $('.modal-body #taskPriorityHolder').css({ borderColor: '' });
                    }

                    this.$http.put('{{ route('api.tasks.update', '') }}/' + id, {
                        name: name,
                        date: date,
                        priority: priority,
                        description: description
                    });

                    main.tasks.forEach(function(item) {
                        if (item.id == id) {
                            item.name = name;
                            item.date = date;
                            item.priority = priority;
                            item.description = description;
                        }
                    });

                    this.taskName = '';
                    this.taskDate = '{{ date("Y-m-d") }}';
                    this.taskPriority = 0;
                    this.taskDescription = '';
                    
                    $('[rel=tooltip]').tooltip()

                    $.notify({
                        icon: 'pe-7s-check',
                        message: "<b>Success!</b> Task has been updated."
                    },{
                        placement: {
                            from: 'bottom'
                        },
                        type: 'success',
                        timer: 2000
                    });

                    this.editModal = false;
                },
                removeTask: function (index, task) {
                	swal({
						title: 'Are you sure?',
						text: "You are about to remove task \"" + task.name + "\".\nYou won't be able to revert this!",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes',
						cancelButtonText: 'No'
					}).then(function() {
						var idx = this.arrayObjectIndexOf(main.tasks, task.id);
	                    main.tasks.splice(idx, 1);

	                    main.$http.delete('{{ route('api.tasks.destroy', '') }}/' + task.id);

	                    $('#today-tasks-notification-' + task.id).siblings('.task.divider:first').remove();
	                    $('#today-tasks-notification-' + task.id).remove();

	                    if(Number($('.notification').text()) == 1) {
	                    	$('.notification').remove();
	                    	$('.todays-tasks').append('<li class="no-task"><a href="{{ route('dashboard') }}">There are no tasks due today.</a></li>')
	                    } else {
                    		$('.notification').text(Number($('.notification').text()) - 1);
	                    }

	                    $.notify({
	                        icon: 'pe-7s-close-circle',
	                        message: "<b>Removed!</b> You have removed \"" + task.name + "\" from your tasks list."
	                    },{
	                        placement: {
	                            from: 'bottom'
	                        },
	                        type: 'danger',
	                        timer: 2000
	                    });
					});
                },
                markTaskAsComplete: function(index, task) {
					var idx = this.arrayObjectIndexOf(main.tasks, task.id);
                    main.tasks[idx].completed = 1;

                    this.$http.patch('{{ route('api.tasks.mark.as.completed', '') }}/' + task.id);

                    $('#today-tasks-notification-' + task.id).siblings('.task.divider:first').remove();
                    $('#today-tasks-notification-' + task.id).remove();

                    if(Number($('.notification').text()) == 1) {
                    	$('.notification').remove();
                    } else {
                		$('.notification').text(Number($('.notification').text()) - 1);
                    }

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
                markTaskAsUncomplete: function(index, task) {
					var idx = this.arrayObjectIndexOf(main.tasks, task.id);
                    main.tasks[idx].completed = 0;

                    this.$http.patch('{{ route('api.tasks.mark.as.uncompleted', '') }}/' + task.id);

                    $.notify({
                        icon: 'pe-7s-check',
                        message: "<b>Success!</b> You have marked \"" + task.name + "\" as uncompleted!"
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
                },
                handleDescription: function(e) {
                    if (e.metaKey || e.ctrlKey) {
                        this.saveTask();
                    }
                },
                toggleDescriptionForm: function() {
                    $('.modal-body .description').toggle();
                }
            }
        });

        var main = new Vue({
            el: '#wrapper',
            components: {
            	datepicker: VueStrap.datepicker,
            },
            data: {
                taskName: '',
                taskDate: '{{ date("Y-m-d") }}',
                taskDescription: '',
                taskPriority: 0,
                searchQuery: '',
                filterTask: '2',
                format: 'yyyy-MM-dd',
                columns: [
                    '#',
                    'name',
                    'priority',
                    'due date',
                    ''
                ],
                tasks: {!! $tasks->toJson() !!},
            },
            methods: {
                addTask: function () {
                    var name = this.taskName.trim();
                    var date = this.taskDate.trim();
                    var priority = this.taskPriority;
                    var description = this.taskDescription.trim();

                    if (!name) {
                    	$('#taskName').css({ borderColor: 'red' });
                    	$('#taskName').focus();
                    	return;
                    } else {
                    	$('#taskName').css({ borderColor: '' });
                    }

                    if (!priority) {
                    	$('#taskPriorityHolder').css({ borderColor: 'red' });
                    	$('#taskPriority').focus();
                    	return;
                    } else {
                    	$('#taskPriorityHolder').css({ borderColor: '' });
                    }

                    this.$http.post('{{ route('api.tasks.save') }}', {
                		name: name,
                		date: date,
                		priority: priority,
                		description: description
                    }).then(function(result) {
                    	if (!result.error) {
		                    this.tasks.unshift({
		                        id: result.data.data.id,
		                        name: name,
		                        date: date,
		                        priority: priority,
		                        description: description
		                    });
                    	}
                    });

                    this.taskName = '';
                    this.taskDate = '{{ date("d M Y") }}';
                    this.taskPriority = 0;
                    this.taskDescription = '';
                    
                    $('[rel=tooltip]').tooltip();
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
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="header">
                    <h4 class="title">My Tasks</h4>
                    <p class="category"></p>
                </div>
                <div class="content">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon" style="border-radius: 0;padding: 0;">
                                <a href="#" id="descriptionToggle" title="Toggle Description Field" v-on:click="toggleDescriptionForm" style="padding: 12px;"><i class="pe-7s-news-paper"></i></a>
                            </div>
                            <input v-model="taskName" v-on:keyup.enter="addTask" class="form-control" placeholder="Enter task name (hit enter to add) ..." style="border-radius: 0" id="taskName" />
                            <div class="input-group-addon" style="padding: 0;" id="taskPriorityHolder">
                                <select v-model="taskPriority" v-on:keyup.enter="addTask" class="form-control" style="width: auto;border: medium none; height: 38px;" id="taskPriority">
                                    <option value="">- Priority -</option>
                                    <option value="1" style="color: red;">Urgent</option>
                                    <option value="2" style="color: blue;">Normal</option>
                                    <option value="3" style="color: green;">Low</option>
                                </select>
                            </div>
                            <div class="input-group-addon" style="border-radius: 0;padding: 0;">
								<datepicker
									:value.sync="taskDate"
									:disabled-days-of-Week="disabled"
									:format="format"
									:show-reset-button="reset"
									v-model="taskDate"
									v-on:keyup.enter="addTask">
								</datepicker>
                            </div>
                        </div>
                        <textarea v-model="taskDescription" class="description form-control" placeholder="Enter task description (hit CTRL/CMD + Enter to add)" style="display:none;min-height:75px;border-top-left-radius: 0;border-top-right-radius: 0;border-top:none;" v-on:keydown.enter="handleDescription($event)"></textarea>
                    </div>
                    <hr />
                    <div class="form-group">
                        <form id="search">
	                    	<div class="row">
	                    		<div class="col-md-7">
		                            <div class="input-group">
		                                <div class="input-group-addon">Search</div>
		                                <input name="query" v-model="searchQuery" class="form-control" placeholder="Enter query to search tasks by name or description ..." />
		                            </div>
	                    		</div>
	                    		<div class="col-md-3 col-md-offset-2">
	                    			<div class="input-group">
	                    				<div class="input-group-addon">See</div>
	                    				<select name="filter" class="form-control" v-model="filterTask">
	                    					<option value="2" selected="">all</option>
	                    					<option value="1">completed</option>
	                    					<option value="0">uncompleted</option>
	                    				</select>
	                    				<div class="input-group-addon">tasks</div>
	                    			</div>
	                    		</div>
	                    	</div>
                        </form>
                    </div>
                    <div class="table-full-width">
                        <task-grid
                            :data="tasks"
                            :columns="columns"
                            :filter-key="searchQuery"
                            :filter-task-key="filterTask"></task-grid>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop