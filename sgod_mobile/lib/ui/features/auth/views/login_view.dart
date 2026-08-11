import 'package:flutter/cupertino.dart';
import 'package:flutter/services.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';
import 'package:provider/provider.dart';

import '../../../../core/theme/app_colors.dart';
import '../../../../core/widgets/app_dialogs.dart';
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
      AppDialogs.alert(context, 'Sign In Failed', vm.error ?? 'Please check your credentials.');
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<AuthViewModel>();
    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: Column(
        children: [
          // ── Navy header band with seal + title + tagline ───────────────
          _buildHeader(),
          // ── Form ───────────────────────────────────────────────────────
          Expanded(
            child: SafeArea(
              top: false,
              child: Center(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 28, vertical: 32),
                  child: ConstrainedBox(
                    constraints: const BoxConstraints(maxWidth: 380),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Text(
                          'Sign in to your account',
                          style: TextStyle(
                            fontSize: 15,
                            color: AppColors.secondaryLabel,
                          ),
                        ),
                        const SizedBox(height: 28),

                        // ── Username ────────────────────────────────────
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

                        // ── Password ────────────────────────────────────
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
                              minimumSize: Size.zero,
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
          ),
        ],
      ),
    );
  }

  /// Navy gradient header band with the DepEd seal, app name, and tagline.
  Widget _buildHeader() {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.light.copyWith(
        statusBarColor: AppColors.primary,
      ),
      child: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [AppColors.primary, AppColors.primaryDark],
          ),
        ),
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: const EdgeInsets.fromLTRB(28, 24, 28, 32),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Image.asset(
                    'assets/icons/DepEd-ONE.png',
                    width: 52,
                    height: 52,
                    fit: BoxFit.contain,
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: const [
                        Text(
                          'ONE DepED',
                          style: TextStyle(
                            fontSize: 26,
                            fontWeight: FontWeight.w700,
                            color: CupertinoColors.white,
                            letterSpacing: 0.3,
                          ),
                        ),
                        SizedBox(height: 2),
                        Text(
                          'Schools Governance & Operations Division',
                          style: TextStyle(
                            fontSize: 12,
                            color: CupertinoColors.white,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 18),
              // Thin gold accent rule — the only place gold appears on login.
              Container(
                width: 48,
                height: 3,
                decoration: BoxDecoration(
                  color: AppColors.accent,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
            ],
          ),
        ),
      ),
      ),
    );
  }
}
