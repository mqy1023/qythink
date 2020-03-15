<template>
  <Row>
    <Col>
      <Card>
        <Row>
          <Table border :columns="columns" :data="data"></Table>
        </Row>
        <Row type="flex" justify="end"  class="page">
          <table-page ref="table-page"></table-page>
        </Row>
      </Card>
    </Col>
  </Row>
</template>
<script>
import tablePage from '../my-components/qythink/table-page'
export default {
  components: { tablePage },
  data () {
    return {
      columns: [
        { title: 'Name', key: 'name' },
        { title: 'Age', key: 'age' },
        { title: 'Address', key: 'address' }
      ],
      data: [
        {
          name: 'John Brown',
          age: 18,
          address: 'New York No. 1 Lake Park',
          date: '2016-10-03'
        },
        {
          name: 'Jim Green',
          age: 24,
          address: 'London No. 1 Lake Park',
          date: '2016-10-01'
        },
        {
          name: 'Joe Black',
          age: 30,
          address: 'Sydney No. 1 Lake Park',
          date: '2016-10-02'
        },
        {
          name: 'Jon Snow',
          age: 26,
          address: 'Ottawa No. 2 Lake Park',
          date: '2016-10-04'
        }
      ]
    }
  }
}
</script>
