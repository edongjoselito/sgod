import 'package:flutter/foundation.dart';

import '../../../../data/models/whereabouts_item.dart';
import '../../../../data/repositories/whereabouts_repository.dart';

/// Manages whereabouts list state — loads data from the API and exposes
/// loading/error states for the view.
class WhereaboutsViewModel extends ChangeNotifier {
  WhereaboutsViewModel(this._repo);

  final WhereaboutsRepository _repo;

  List<WhereaboutsItem> _items = [];
  List<WhereaboutsItem> get items => _items;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  /// Loads whereabouts from the API. Pass [silent] to skip the loading
  /// indicator (used by pull-to-refresh).
  Future<void> load({bool silent = false}) async {
    if (!silent) {
      _isLoading = true;
      _error = null;
      notifyListeners();
    }
    try {
      _items = await _repo.fetch(limit: 50, offset: 0);
    } catch (e) {
      _error = '$e';
      debugPrint('WhereaboutsViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
