import 'dart:async';

import 'package:connectivity_plus/connectivity_plus.dart';

/// Streams the device's online/offline state so ViewModels and the sync
/// engine can react when connectivity changes.
///
/// Exposes a synchronous [isOnline] snapshot (based on the last event) and
/// a broadcast [stream] that emits on every change.
class ConnectivityService {
  ConnectivityService({Connectivity? connectivity})
      : _connectivity = connectivity ?? Connectivity() {
    _connectivity.onConnectivityChanged.listen(_onChanged);
  }

  final Connectivity _connectivity;
  final _controller = StreamController<bool>.broadcast();

  bool _isOnline = true;
  bool get isOnline => _isOnline;

  Stream<bool> get stream => _controller.stream;

  void _onChanged(List<ConnectivityResult> results) {
    final online = results.any((r) =>
        r == ConnectivityResult.wifi ||
        r == ConnectivityResult.mobile ||
        r == ConnectivityResult.ethernet);
    if (online != _isOnline) {
      _isOnline = online;
      _controller.add(online);
    }
  }

  /// Force a fresh check — useful on app resume.
  Future<bool> check() async {
    final results = await _connectivity.checkConnectivity();
    _onChanged(results);
    return _isOnline;
  }

  void dispose() {
    _controller.close();
  }
}
