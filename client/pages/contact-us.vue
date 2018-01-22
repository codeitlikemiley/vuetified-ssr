<template>
    <v-card :flat="true">
      <v-toolbar class="accent">
        <v-btn 
          flat 
          icon 
          color="white"
          @click.native="redirectBack()"
        >
          <v-icon>arrow_back</v-icon>
        </v-btn>
        <v-spacer/>
        <v-toolbar-title class="text-xs-center white--text">{{ toolbarTitle }}</v-toolbar-title>
        <v-spacer/>
        <v-toolbar-items>
          <!-- If There is no User Account Login Yet Redirect to Authentication Page -->
          <v-btn 
            flat 
            color="white" 
            @click.native="goHome()"
          >
            <v-icon>home</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text>
        <v-container fluid>
          <v-layout 
            row 
            wrap
          >
            <!-- left side -->
            <v-flex 
              d-flex 
              xs12 
              sm12 
              md6 
              lg6
            >
              <v-layout 
                row 
                wrap
              >
                <v-flex 
                  d-flex 
                  xs12 
                >
                  <v-container 
                    fill-height 
                    fluid
                  >
                    
                    <v-layout fill-height>
                      <v-flex 
                        xs12 
                        align-end 
                        flexbox
                      >
                        <v-form @submit.prevent="submit" v-model="valid" ref="form" lazy-validation>
                          <p class="headline accent--text">{{ $t('contact_us') }}</p>
                          <v-text-field
                            light
                            name="name"
                            v-model="form.name"
                            :label="$t('name')"
                            :class="{ 'is-invalid': form.errors.has('name') }"
                            :rules="nameRules()"
                          />
                          <v-text-field
                            light
                            name="email"
                            v-model="form.email"
                            :label="$t('email')"
                            :class="{ 'is-invalid': form.errors.has('email') }"
                            :rules="emailRules()"
                          />
                          <v-text-field
                            light
                            name="subject"
                            v-model="form.subject"
                            :label="$t('subject')"
                            :class="{ 'is-invalid': form.errors.has('subject') }"
                            :rules="subjectRules()"
                          />
                          <v-text-field
                            light
                            name="message"
                            v-model="form.message"
                            multi-line
                            :label="$t('message')"
                            :class="{ 'is-invalid': form.errors.has('message') }"
                            :rules="messageRules()"
                          />
                          <v-btn 
                            :loading="form.busy"
                            :disabled="!valid" 
                            type="submit" 
                            :color="indicator"
                            block
                          >
                            {{ $t('send') }}
                            <v-icon right>send</v-icon>
                          </v-btn>
                        </v-form>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex 
              d-flex 
              xs12 
              sm12 
              md6 
              lg6
            >
              <v-layout 
                row 
                wrap
              >
                <v-flex 
                  d-flex 
                  xs12
                >
                  <v-card 
                    flat 
                    light 
                  >
                    <v-card-title class="headline accent--text">{{ $t('contact_details') }}</v-card-title></v-card-title>
                    <v-card-text class="headline accent--text">
                      <v-icon color="red">place</v-icon> {{ address }}
                    </v-card-text>
                    <v-card-text class="headline accent--text">
                      <v-icon color="indigo">location_city</v-icon> {{ city }}
                    </v-card-text>
                    <v-card-text class="headline accent--text">
                      <v-icon color="info">map</v-icon> {{ state }}
                    </v-card-text>
                    <v-card-text class="headline accent--text">
                      <v-icon color="light-blue">language</v-icon> {{ country }}
                    </v-card-text>
                    <v-card-text class="headline accent--text">
                      <v-icon color="brown">phone</v-icon>{{ contact_no }}
                    </v-card-text>
                    <v-card-text class="headline accent--text">
                      <v-icon color="yellow darken-2">mail</v-icon><span v-text="email"/>
                    </v-card-text>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
    </v-card>
</template>

<script>
import { Form } from 'vform'
export default {
  head () {
    return { title: this.$t('contact_us') }
  },
  data: () => ({
    valid: true,
    form: new Form({
      name: '',
      email: '',
      subject: '',
      message: ''
    }),
    nameRules () {
      return [
        (v) => !!v || this.$t('name_is_required')
      ]
    },
    emailRules () {
      return [
        (v) => !!v || this.$t('email_is_required'),
        (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t('email_must_be_valid')
      ]
    },
    subjectRules () {
      return [
        (v) => !!v || this.$t('subject_is_required')
      ]
    },
    messageRules () {
      return [
        (v) => !!v || this.$t('message_is_required')
      ]
    }
  }),
  computed: {
    toolbarTitle () {
      return this.$t('support')
    },
    indicator () {
      if (this.form.busy) {
        return 'error'
      } else {
        return 'accent'
      }
    },
    address () {
      return this.$env.SITE_ADDRESS
    },
    city () {
      return this.$env.SITE_CITY
    },
    state () {
      return this.$env.SITE_STATE
    },
    country () {
      return this.$env.SITE_COUNTRY
    },
    'contact_no' () {
      return this.$env.SITE_CONTACT_NO
    },
    email () {
      return this.$env.SITE_EMAIL
    }

  },
  methods: {
    resetForm () {
      this.form = new Form({
        name: '',
        email: '',
        subject: '',
        message: ''
      })
    },
    async submit () {
      const self = this

      if (this.$refs.form.validate()) {
        this.form.busy = true
        try {
          await this.form.post('/contact-us')
          self.resetForm()
          self.$router.push('/')
        } catch ({ message }) {
          console.log(message)
        }
        self.form.busy = false
      }
    },
    redirectBack () {
      const self = this
      return self.$nextTick(() => self.$router.go(-1))
    },
    goHome () {
      const self = this
      self.$nextTick(() => self.$router.push({ name: 'home' }))
    }
  }
}
</script>

