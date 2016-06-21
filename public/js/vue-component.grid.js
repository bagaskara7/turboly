var grid = Vue.component('task-grid', {
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
        saveTask: function(index, task) {
            this.$http.put('{{ route('api.tasks.update', '') }}/' + task.id);

            $.notify({
                icon: 'pe-7s-close-circle',
                message: "<b>Success!</b> Task has been updated."
            },{
                placement: {
                    from: 'bottom'
                },
                type: 'danger',
                timer: 2000
            });
        },
        removeTask: function (index, task) {
        	swal({
				title: 'Are you sure?',
				text: "You are about to delete task \"" + task.name + "\".\nYou won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			}).then(function() {
                main.tasks.splice(index, 1);

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
                    message: "<b>Deleted!</b> You have deleted \"" + task.name + "\" from your tasks list."
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
            main.tasks.splice(index, 1);

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