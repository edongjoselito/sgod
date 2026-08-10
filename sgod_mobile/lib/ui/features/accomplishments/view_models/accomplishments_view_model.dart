import 'package:flutter/foundation.dart';

import '../../../../data/models/accomplishment_item.dart';
import '../../../../data/repositories/accomplishments_repository.dart';

/// Manages accomplishments list state — loads data from the API, handles
/// loading/error states, supports pull-to-refresh, and exposes a filtered
/// view driven by the search query.
class AccomplishmentsViewModel extends ChangeNotifier {
  AccomplishmentsViewModel(this._repo, {String section = ''})
      : _section = section;

  final AccomplishmentsRepository _repo;

  final String _section;

  List<AccomplishmentItem> _items = const [];
  List<AccomplishmentItem> get items => _filtered;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  String _query = '';
  String get query => _query;

  set query(String value) {
    if (_query == value) return;
    _query = value;
    notifyListeners();
  }

  /// The full unfiltered list.
  List<AccomplishmentItem> get all => _items;

  List<AccomplishmentItem> get _filtered {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return _items;
    return _items.where((a) {
      return a.activity.toLowerCase().contains(q) ||
          a.section.toLowerCase().contains(q) ||
          a.dateConducted.toLowerCase().contains(q);
    }).toList();
  }

  Future<void> load() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _items = await _repo.fetch(section: _section);
    } catch (e) {
      _error = '$e';
      debugPrint('AccomplishmentsViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
