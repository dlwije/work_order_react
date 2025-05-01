import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register"/>

            <form onSubmit={submit} className="card card-body login-card-body">
                <div className="input-group mb-3">
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value={data.name}
                        className="form-control"
                        placeholder="Name"
                        autoComplete="name"
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />
                    <div className="input-group-text">
                        <i className="bi bi-person-fill"></i>
                    </div>
                </div>
                <InputError message={errors.name} className="text-danger small mb-3"/>

                <div className="input-group mb-3">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value={data.email}
                        className="form-control"
                        placeholder="Email"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    <div className="input-group-text">
                        <i className="bi bi-envelope-fill"></i>
                    </div>
                </div>
                <InputError message={errors.email} className="text-danger small mb-3"/>

                <div className="input-group mb-3">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        value={data.password}
                        className="form-control"
                        placeholder="Password"
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    <div className="input-group-text">
                        <i className="bi bi-lock-fill"></i>
                    </div>
                </div>
                <InputError message={errors.password} className="text-danger small mb-3"/>

                <div className="input-group mb-3">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        className="form-control"
                        placeholder="Confirm Password"
                        autoComplete="new-password"
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        required
                    />
                    <div className="input-group-text">
                        <i className="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <InputError
                    message={errors.password_confirmation}
                    className="text-danger small mb-3"
                />

                <div className="d-flex justify-content-between align-items-center mt-3">
                    <Link href={route('login')} className="text-sm text-muted">
                        Already registered?
                    </Link>

                    <button type="submit" className="btn btn-primary" disabled={processing}>
                        Register
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
