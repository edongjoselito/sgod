import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/primary_button.dart';
import '../view_models/auth_view_model.dart';

/// iOS-style login screen — CupertinoTextField, filled button, clean layout.
/// The server auto-detects whether the account is in DepEd MIS or SGOD ONE.
class LoginView extends StatefulWidget {
  const LoginView({super.key});

  @override
  State<LoginView> createState() => _LoginViewState();
}

class _LoginViewState extends State<LoginView> {
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    final vm = context.read<AuthViewModel>();
    final ok = await vm.login(
      username: _usernameController.text.trim(),
      password: _passwordController.text,
    );
    if (!ok && mounted) {
      showCupertinoDialog(
        context: context,
        builder: (ctx) => CupertinoAlertDialog(
          title: const Text('Sign In Failed'),
          content: Text(vm.error ?? 'Please check your credentials.'),
          actions: [
            CupertinoDialogAction(
              isDefaultAction: true,
              onPressed: () => Navigator.pop(ctx),
              child: const Text('OK'),
            ),
          ],
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<AuthViewModel>();
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 48),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 380),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text(
                    'DepEd ONE',
                    style: TextStyle(
                      fontSize: 34,
                      fontWeight: FontWeight.w700,
                      color: AppColors.label,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Sign in to your account',
                    style: TextStyle(
                      fontSize: 15,
                      color: AppColors.secondaryLabel,
                    ),
                  ),
                  const SizedBox(height: 36),

                  // ── Username ────────────────────────────────────────
                  Container(
                    decoration: BoxDecoration(
                      color: AppColors.surface,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: CupertinoTextField(
                      controller: _usernameController,
                      placeholder: 'Username or ID',
                      prefix: const Padding(
                        padding: EdgeInsets.only(left: 16),
                        child: Icon(CupertinoIcons.person, size: 20,
                            color: AppColors.tertiaryLabel),
                      ),
                      padding: const EdgeInsets.symmetric(
                          horizontal: 16, vertical: 16),
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      textInputAction: TextInputAction.next,
                      style: const TextStyle(
                        fontSize: 17,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // ── Password ────────────────────────────────────────
                  Container(
                    decoration: BoxDecoration(
                      color: AppColors.surface,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: CupertinoTextField(
                      controller: _passwordController,
                      placeholder: 'Password',
                      obscureText: _obscurePassword,
                      prefix: const Padding(
                        padding: EdgeInsets.only(left: 16),
                        child: Icon(CupertinoIcons.lock, size: 20,
                            color: AppColors.tertiaryLabel),
                      ),
                      suffix: CupertinoButton(
                        padding: const EdgeInsets.only(right: 12),
                        minSize: 0,
                        onPressed: () => setState(
                            () => _obscurePassword = !_obscurePassword),
                        child: Icon(
                          _obscurePassword
                              ? CupertinoIcons.eye
                              : CupertinoIcons.eye_slash,
                          size: 20,
                          color: AppColors.tertiaryLabel,
                        ),
                      ),
                      padding: const EdgeInsets.symmetric(
                          horizontal: 16, vertical: 16),
                      decoration: BoxDecoration(
                        color: AppColors.surface,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      onSubmitted: (_) => _submit(),
                      style: const TextStyle(
                        fontSize: 17,
                        color: AppColors.label,
                      ),
                    ),
                  ),
                  const SizedBox(height: 28),

                  PrimaryButton(
                    label: 'Sign In',
                    icon: PhosphorIconsRegular.signIn,
                    loading: vm.isLoading,
                    onPressed: _submit,
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
