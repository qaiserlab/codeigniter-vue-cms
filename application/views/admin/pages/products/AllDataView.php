<div id="PageActive" class="content-wrapper">

  <!-- Headbar -->
  <ui-header title="<?= $title ?>" caption="<?= $caption ?>">
    <ol class="breadcrumb">
      <li><a href="<?= base_url('admin') ?>"><i class="fa fa-dashboard"></i></a></li>
      <li class="active"><?= $title ?></li>
    </ol>
  </ui-header>
  <!-- End Headbar -->

  <!-- Content -->
  <div class="content">

    <!-- Table Area -->
    <ui-widget title="<?= lang('TABLE') ?>" :loading="loading">

      <!-- Toolbar -->
      <div class="box-header">

        <ul class="list-inline pull-left">
          <li>
            <ui-datepicker type="monthly"
            v-model="postedOn"
            @input="refreshData"></ui-datepicker>
          </li>
        </ul>

        <ul class="list-inline pull-right">
          <li>
            <ui-link @click="refreshData" icon="refresh"><?= lang('REFRESH') ?></ui-link>
          </li>
          <li>
            <ui-link @click="addNewData" icon="plus"><?= lang('ADD_NEW_DATA') ?></ui-link>
          </li>
        </ul>

      </div>
      <!-- End Toolbar -->

      <div class="box-body">

        <ui-data-table :data-source="rs">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th><?= lang('TITLE') ?></th>
                <th class="hidden-xs"><?= lang('PUBLICATION') ?></th>
                <th class="hidden-xs"><?= lang('POSTED_ON') ?></th>
                <th style="text-align: right"><?= lang('ACTION') ?></th>
              </tr>
            </thead>
            <tbody>

              <tr v-for="row in rs">
                <td>{{ row.title }}</td>
                <td class="hidden-xs">{{ row.publication }}</td>
                <td class="hidden-xs">{{ row._postedOn }}</td>
                <td>
                  <ul class="list-inline pull-right">
                    <li>
                      <ui-link
                      :href="'javascript:vm.editData(' + row.id + ')'"
                      icon="edit"><?= lang('EDIT') ?></ui-link>
                    </li>
                    <li>
                      <ui-link
                      :href="'javascript:vm.deleteData(' + row.id + ')'"
                      icon="remove"><?= lang('DELETE') ?></ui-link>
                    </li>
                  </ul>
                </td>
              </tr>

            </tbody>
          </table>
        </ui-data-table>

      </div>

    </ui-widget>
    <!-- End Table Area -->

  </div>
  <!-- End Content -->

</div>

<script>
  var vm = new Vue({
    el: '#PageActive',

    /************************
    <id="_data">
    ************************/
    data: {
      loading: false,

      postedOn: '<?= date('Y-m-d') ?>',
      rs: [],

    },

    /************************
    <id="_created">
    ************************/
    created: function () {
      this.refreshData();
    },

    /************************
    <id="_methods">
    ************************/
    methods: {

      /************************
      <id="_refreshData">
      ************************/
      refreshData: function () {
        this.loading = true;

        var xPostedOn = this.postedOn.split('-');
        var postedYear = xPostedOn[0];
        var postedMonth = xPostedOn[1];

        var url = 'ProductController/readAllData';
        var data = {
          remap: true,
          orderBy: 'postedOn desc',
          where: 'year(postedOn) = ' + postedYear + ' and month(postedOn) = ' + postedMonth,
        };

        $api.post(url, data, function (result) {

          this.rs = result.data;
          this.loading = false;

        }.bind(this));
      },

      /************************
      <id="_addNewData">
      ************************/
      addNewData: function () {
        window.location = admin_url('products/add-new-data');
      },

      /************************
      <id="_editData">
      ************************/
      editData: function (id) {
        window.location = admin_url('products/edit-data/' + id);
      },

      /************************
      <id="_deleteData">
      ************************/
      deleteData: function (id) {

        $confirm.show('<?= lang('DELETE_SELECTED_DATA') ?>', function () {

          var url = 'ProductController/deleteData';
          var data = {
            id: id,
          };

          $api.post(url, data, function (result) {
            this.refreshData();
          }.bind(this));

        }.bind(this));

      },
    },

  });
</script>
