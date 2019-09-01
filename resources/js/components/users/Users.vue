<template>
  <div>
    <div class="title">Users Management</div>

    <div class="users">
      <section id="form">
        <form @submit.prevent="onSubmit" autocomplete="off">
          <div class="form-group">
            <label for="name">Name:</label>
            <input
              type="text"
              class="form-control"
              id="name"
              v-model="form.name"
              @keydown="errors.clear('name')"
            />
            <span
              class="error text-danger"
              v-text="errors.get('name')"
              v-if="errors.has('name')"
            >Name is invalid</span>
          </div>

          <div class="form-group">
            <label for="username">Username:</label>
            <input
              type="text"
              class="form-control"
              id="username"
              v-model="form.username"
              @keydown="errors.clear('username')"
            />
            <span
              class="error text-danger"
              v-text="errors.get('username')"
              v-if="errors.has('username')"
            >Username is invalid</span>
          </div>

          <div class="form-group">
            <label for="username">Email:</label>
            <input
              type="text"
              class="form-control"
              id="email"
              v-model="form.email"
              @keydown="errors.clear('email')"
            />
            <span
              class="error text-danger"
              v-text="errors.get('email')"
              v-if="errors.has('email')"
            >Email is invalid</span>
          </div>

          <button class="btn btn-info btn-block my-4" :disabled="errors.any()">{{ saveButton }}</button>
        </form>
      </section>

      <section id="list">
        <jsonUser @newUser="addUser($event)" />
        <hr />
        <p v-if="!usersList">There are no users to manage. Add them.</p>

        <div class="list-titles">
          <div>Name</div>
          <div>Username</div>
          <div>Email</div>
          <div>Actions</div>
        </div>
        <hr />

        <div class="list-users-wrapper">
          <div v-for="(user, index) in usersList" :key="user.id">
            <div class="list-users-items">
              <div class="list-item-name">{{user.name}}</div>
              <div class="list-item-username">{{ user.username }}</div>
              <div class="list-item-email">{{ user.email }}</div>
              <div class="list-item-actions">
                <button class="btn btn-sm btn-info" @click="onEdit(user, index)">Edit</button>
                <button class="btn btn-sm btn-danger" @click="onDelete(user.id, index)">Delete</button>
              </div>
            </div>
            <hr />
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import Errors from "../../utilities/Errors.js";
import JsonUser from "./JsonUser.vue";
export default {
  components: { JsonUser },
  data() {
    return {
      usersList: [],
      form: {
        id: null,
        name: null,
        username: null,
        email: null
      },
      edit: false,
      index: null,
      errors: new Errors()
    };
  },
  methods: {
    onSubmit() {
      this.edit ? this.onUpdate() : this.onCreate();
    },
    onCreate() {
      const formedForm = {
        name: this.form.name,
        username: this.form.username,
        email: this.form.email
      };

      axios
        .post("/api/users", formedForm)
        .then(response => this.addUser(response.data.data))
        .catch(error => this.errors.record(error.response.data.errors));
    },
    addUser(user) {
      this.usersList.unshift(user);
      this.resetForm();
    },
    onUpdate() {
      const formedForm = {
        id: this.form.id,
        name: this.form.name,
        username: this.form.username,
        email: this.form.email
      };

      axios
        .patch("/api/users", formedForm)
        .then(response => this.editUser(response.data.data, this.index))
        .catch(error => console.log(error));
    },
    editUser(user, index) {
      this.usersList.splice(index, 1);
      this.usersList.unshift(user);
      this.resetForm();
      this.edit = false;
    },
    onEdit(user, index) {
      this.edit = true;
      this.index = index;
      this.form.id = user.id;
      this.form.name = user.name;
      this.form.username = user.username;
      this.form.email = user.email;
    },
    onDelete(id, index) {
      axios
        .delete(`/api/users/${id}`)
        .then(response => this.usersList.splice(index, 1));
    },
    getUsersList() {
      axios
        .get("/api/users")
        .then(response => (this.usersList = response.data.data));
    },
    resetForm() {
      this.form.id = null;
      this.form.name = null;
      this.form.username = null;
      this.form.email = null;
    }
  },
  computed: {
    saveButton() {
      return this.edit ? "Update" : "Create";
    }
  },
  mounted() {
    this.getUsersList();
  }
};
</script>

<style lang="scss" scoped>
@import "../../../sass/app.scss";

.title {
  font-size: 2em;
  text-align: center;
  color: color(white-color);
  padding: 1em 0 1em 0;

  @include desktop {
    padding: 1em 15em 0 0;
  }
}

.users {
  @include desktop {
    display: grid;
    grid-template-columns: 65% auto;
    grid-template-areas: "list form";
  }

  section#form {
    background: #fff;
    padding: 20px;
    margin: 0em auto;
    border-radius: $borders;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 70%;
    align-self: center;

    @include desktop {
      grid-area: form;
      height: fir-content;
      align-self: center;
      margin-bottom: 80%;
    }

    label {
      font-size: 0.8em;
    }

    span {
      font-size: 0.7em;
    }
  }

  section#list {
    padding: $padding;

    @include desktop {
      color: color(white-color);
    }

    .list-titles {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      justify-items: center;
      
    }

    .list-users-wrapper {
      margin-top: 1em;
      display: grid;
      grid-template-columns: 100%;
      grid-gap: 1em;
    }

    .list-users-items {
      display: grid;
      grid-template-columns: repeat(4, 25%);
      padding-bottom: 1em;
      grid-gap: 1em;
    }

    hr {
      background-color: color(white-color);
      align-self: center;
      margin: 0;
      padding: 0;
    }

    .list-item-name {
      grid-column: 1;
      align-self: center;
    }

    .list-item-username {
      grid-column: 2;
      align-self: center;
    }

    .list-item-email {
      grid-column: 3;
      align-self: center;
    }

    .list-item-actions {
      display: grid;
      grid-template-rows: 1fr 1fr;
      grid-column: 4;
      margin-left: 4em;

      .btn-info {
        justify-items: right;
        width: 50%;
        grid-row: 1;
      }

      .btn-danger {
        width: 50%;
        grid-row: 2;
      }
    }
  }

  hr {
    background-color: color(white-color);
    align-self: center;
  }
}
</style>
