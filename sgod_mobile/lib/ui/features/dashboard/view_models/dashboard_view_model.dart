import 'package:flutter/foundation.dart';

import '../../../../data/models/dashboard_data.dart';
import '../../../../data/repositories/dashboard_repository.dart';

/// Manages dashboard state — loads data from the API, handles loading/
/// error states, and supports pull-to-refresh.
class DashboardViewModel extends ChangeNotifier {
  DashboardViewModel(this._repo);

  final DashboardRepository _repo;

  DashboardData? _data;
  DashboardData? get data => _data;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  Future<void> load() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _data = await _repo.fetch();
    } catch (e) {
      _error = '$e';
      debugPrint('DashboardViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
