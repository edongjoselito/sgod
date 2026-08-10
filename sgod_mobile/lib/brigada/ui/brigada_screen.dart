import 'package:flutter/cupertino.dart';
import 'package:phosphor_flutter/phosphor_flutter.dart';

import '../../core/network/api_exception.dart';
import '../../core/theme/app_colors.dart';
import '../brigada_module.dart';
import '../data/brigada_repository.dart';
import 'brigada_ui.dart';

/// Two-phase loader shared by every Brigada screen.
///
/// [load] paints from the offline cache first (so navigation is instant and
/// works with no signal), then revalidates against the server and swaps in the
/// fresh payload. [refresh] skips straight to the network for pull-to-refresh.
class BrigadaLoader<T> extends ChangeNotifier {
  BrigadaLoader({required this.request, required this.parse});

  /// Rebuildable so screens can re-point the loader when a filter changes.
  BrigadaRequest request;
  final T Function(Map<String, dynamic>) parse;

  BrigadaRepository get _repo => BrigadaModule.repository;

  BrigadaResult<T>? _result;
  BrigadaResult<T>? get result => _result;
  T? get data => _result?.data;
  bool get hasData => _result != null;

  bool _loading = false;
  bool get isLoading => _loading;

  Object? _error;
  Object? get error => _error;

  /// Guards against a slow response from a previous filter overwriting the
  /// payload for the filter the user has since selected.
  int _generation = 0;

  /// Point the loader at a different request (e.g. a new school year) and
  /// reload. Clears the current payload so the UI never mixes two periods.
  Future<void> retarget(BrigadaRequest next) async {
    if (next == request) return;
    request = next;
    _result = null;
    _error = null;
    notifyListeners();
    await load();
  }

  /// Cache first, then network.
  Future<void> load() async {
    final generation = ++_generation;
    _loading = true;
    _error = null;
    notifyListeners();

    // Screens can be opened straight from the sidebar, so the module may not
    // have been initialised yet.
    try {
      await BrigadaModule.ensureInitialized();
    } catch (e) {
      if (generation != _generation) return;
      _error = e;
      _loading = false;
      notifyListeners();
      return;
    }

    try {
      final cached = await _repo.cached(request, parse);
      if (generation != _generation) return;
      if (cached != null) {
        _result = cached;
        notifyListeners();
      }
    } catch (_) {
      // A bad cache entry is never fatal — the network pass decides.
    }

    await _fetch(generation);
  }

  /// Network only — pull-to-refresh and explicit retries.
  Future<void> refresh() async {
    final generation = ++_generation;
    _loading = true;
    notifyListeners();
    try {
      await BrigadaModule.ensureInitialized();
    } catch (e) {
      if (generation != _generation) return;
      if (_result == null) _error = e;
      _loading = false;
      notifyListeners();
      return;
    }
    await _fetch(generation);
  }

  Future<void> _fetch(int generation) async {
    try {
      final fresh = await _repo.fetch(request, parse);
      if (generation != _generation) return;
      _result = fresh;
      _error = null;
    } catch (e) {
      if (generation != _generation) return;
      // Keep whatever the cache gave us; only surface a hard error when the
      // screen has nothing at all to show.
      if (_result == null) _error = e;
    } finally {
      if (generation == _generation) {
        _loading = false;
        notifyListeners();
      }
    }
  }
}

/// Standard page shell for the module: large iOS title, pull-to-refresh, a
/// freshness strip, and consistent loading / error / empty handling.
///
/// [builder] returns the page body; it is only called once data exists.
class BrigadaScreen<T> extends StatefulWidget {
  const BrigadaScreen({
    super.key,
    required this.title,
    required this.loader,
    required this.builder,
    this.previousPageTitle,
    this.trailing,
    this.header,
    this.emptyCheck,
    this.emptyIcon = PhosphorIconsRegular.tray,
    this.emptyTitle = 'Nothing to show',
    this.emptyMessage,
  });

  final String title;
  final String? previousPageTitle;
  final BrigadaLoader<T> loader;

  /// Page body, built from the loaded payload.
  final Widget Function(BuildContext context, T data) builder;

  /// Optional widget pinned above the scrolling body (filters, search).
  final Widget Function(BuildContext context, T data)? header;

  final Widget? trailing;

  /// Return true when the payload holds no rows, to show the empty state.
  final bool Function(T data)? emptyCheck;
  final IconData emptyIcon;
  final String emptyTitle;
  final String? emptyMessage;

  @override
  State<BrigadaScreen<T>> createState() => _BrigadaScreenState<T>();
}

class _BrigadaScreenState<T> extends State<BrigadaScreen<T>> {
  @override
  void initState() {
    super.initState();
    widget.loader.addListener(_onChanged);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!widget.loader.hasData) widget.loader.load();
    });
  }

  @override
  void dispose() {
    widget.loader.removeListener(_onChanged);
    super.dispose();
  }

  void _onChanged() {
    if (mounted) setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    final loader = widget.loader;
    final result = loader.result;

    return CupertinoPageScaffold(
      backgroundColor: AppColors.background,
      child: CustomScrollView(
        slivers: [
          CupertinoSliverNavigationBar(
            largeTitle: Text(widget.title),
            previousPageTitle: widget.previousPageTitle,
            backgroundColor: AppColors.surface,
            border: const Border(
              bottom: BorderSide(color: AppColors.separator, width: 0.5),
            ),
            trailing: widget.trailing,
          ),
          CupertinoSliverRefreshControl(onRefresh: loader.refresh),
          SliverSafeArea(
            top: false,
            minimum: const EdgeInsets.only(bottom: 32),
            sliver: SliverToBoxAdapter(child: _body(context, result)),
          ),
        ],
      ),
    );
  }

  Widget _body(BuildContext context, BrigadaResult<T>? result) {
    final loader = widget.loader;

    if (result == null) {
      if (loader.isLoading) {
        return const Padding(
          padding: EdgeInsets.symmetric(vertical: 80),
          child: Center(child: CupertinoActivityIndicator(radius: 14)),
        );
      }
      return _errorState(loader.error);
    }

    final data = result.data;
    final isEmpty = widget.emptyCheck?.call(data) ?? false;

    return Padding(
      padding: BrigadaTokens.pagePadding,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          BrigadaFreshnessBar(
            isOnline: BrigadaModule.isOnline,
            fromCache: result.fromCache,
            fetchedAt: result.fetchedAt,
            refreshError: result.refreshError,
            onRetry: loader.refresh,
          ),
          if (widget.header != null) widget.header!(context, data),
          if (isEmpty)
            BrigadaEmpty(
              icon: widget.emptyIcon,
              title: widget.emptyTitle,
              message: widget.emptyMessage,
            )
          else
            widget.builder(context, data),
        ],
      ),
    );
  }

  Widget _errorState(Object? error) {
    if (error is BrigadaOfflineException) {
      return BrigadaEmpty(
        icon: PhosphorIconsRegular.wifiSlash,
        title: 'Not available offline',
        message:
            'This report has not been downloaded yet. Connect to the network, '
            'or use Download for offline from the Brigada Eskwela screen.',
        actionLabel: 'Try again',
        onAction: widget.loader.refresh,
      );
    }

    final message = error is ApiException
        ? error.message
        : error == null
            ? 'Something went wrong.'
            : '$error';

    return BrigadaEmpty(
      icon: PhosphorIconsRegular.warningCircle,
      title: "Couldn't load this report",
      message: message,
      actionLabel: 'Try again',
      onAction: widget.loader.refresh,
    );
  }
}
