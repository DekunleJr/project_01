<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="firstname">Firstname</Label>
                    <Input
                        id="firstname"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="firstname"
                        name="firstname"
                        placeholder="Firstname"
                    />
                    <InputError :message="errors.firstname" />
                </div>

                <div class="grid gap-2">
                    <Label for="lastname">Lastname</Label>
                    <Input
                        id="lastname"
                        type="text"
                        required
                        autofocus
                        :tabindex="2"
                        autocomplete="lastname"
                        name="lastname"
                        placeholder="Lastname"
                    />
                    <InputError :message="errors.lastname" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="3"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="number">Number</Label>
                    <Input
                        id="number"
                        type="text"
                        required
                        autofocus
                        :tabindex="4"
                        autocomplete="number"
                        name="number"
                        placeholder="Phone Number"
                    />
                    <InputError :message="errors.number" />
                </div>

                <div class="grid gap-2">
                    <Label for="bvn">BVN</Label>
                    <Input
                        id="bvn"
                        type="text"
                        required
                        autofocus
                        :tabindex="5"
                        autocomplete="bvn"
                        name="bvn"
                        placeholder="BVN"
                    />
                    <InputError :message="errors.bvn" />
                </div>

                <div class="grid gap-2">
                    <Label for="nin">NIN</Label>
                    <Input
                        id="nin"
                        type="text"
                        required
                        autofocus
                        :tabindex="6"
                        autocomplete="nin"
                        name="nin"
                        placeholder="NIN"
                    />
                    <InputError :message="errors.nin" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="7"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>


                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="7"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Create account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="6"
                    >Log in</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
