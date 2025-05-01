import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in"/>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="card card-body login-card-body shadow">
                <p className="login-box-msg text-center">Sign in to start your session</p>

                <div className="input-group mb-3">
                    <TextInput
                        type="email"
                        name="email"
                        value={data.email}
                        className="form-control"
                        placeholder="Email"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    <div className="input-group-text">
                        <i className="bi bi-envelope"></i>
                    </div>
                </div>
                <InputError message={errors.email} className="text-danger mb-2"/>

                <div className="input-group mb-3">
                    <TextInput
                        type="password"
                        name="password"
                        value={data.password}
                        className="form-control"
                        placeholder="Password"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    <div className="input-group-text">
                        <i className="bi bi-lock-fill"></i>
                    </div>
                </div>
                <InputError message={errors.password} className="text-danger mb-2"/>

                <div className="row mb-3">
                    <div className="col-8">
                        <div className="form-check">
                            <Checkbox
                                name="remember"
                                checked={data.remember}
                                onChange={(e) => setData('remember', e.target.checked)}
                                className="form-check-input"
                            />
                            <label htmlFor="remember" className="form-check-label ms-2">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <div className="col-4 d-grid">
                        <PrimaryButton className="btn btn-primary" disabled={processing}>
                            {processing ? 'Logging in...' : 'Sign In'}
                        </PrimaryButton>
                    </div>
                </div>

                <div className="text-center mb-3 d-grid gap-2">
                    <p>- OR -</p>
                    <a href="#" className="btn btn-primary">
                        <i className="bi bi-facebook me-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" className="btn btn-danger">
                        <i className="bi bi-google me-2"></i> Sign in using Google+
                    </a>
                </div>

                {canResetPassword && (
                    <p className="mb-1">
                        <Link href={route('password.request')} className="text-decoration-none">
                            I forgot my password
                        </Link>
                    </p>
                )}

                <p className="mb-0">
                    <Link href={route('register')} className="text-decoration-none">
                        Register a new membership
                    </Link>
                </p>
            </form>
        </GuestLayout>
    );
}
